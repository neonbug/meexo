<?php namespace Neonbug\Gallery\Controllers;

use Request;
use Auth;
use App;
use Log;

use Neonbug\Gallery\Models\GalleryImage as GalleryImage;

class AdminController extends \Neonbug\Common\Http\Controllers\BaseAdminController {
	
	const CONFIG_PREFIX = 'neonbug.gallery';
	private $model;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->model = config(static::CONFIG_PREFIX . '.model');
	}
	
	protected function getModel()        { return $this->model; }
	protected function getRepository()   { return '\Neonbug\Gallery\Repositories\GalleryRepository'; }
	protected function getConfigPrefix() { return self::CONFIG_PREFIX; }
	protected function getRoutePrefix()  { return 'gallery'; }
	protected function getPackageName()  { return 'gallery'; }
	protected function getListTitle()    { return [ 
		trans($this->getPackageName() . '::admin.title.main'), 
		trans($this->getPackageName() . '::admin.title.list')
	]; }
	protected function getAddTitle()     { return [ 
		trans($this->getPackageName() . '::admin.title.main'), 
		trans($this->getPackageName() . '::admin.title.add')
	]; }
	protected function getEditTitle()    { return [ 
		trans($this->getPackageName() . '::admin.title.main'), 
		trans($this->getPackageName() . '::admin.title.edit')
	]; }
	
	public function adminAddPost()
	{
		$is_preview = (Request::input('preview') !== null);
		
		$model = $this->getModel();
		$item = new $model();
		
		$retval = $this->adminAddPostHandle(
			$is_preview, 
			$item, 
			Request::input('field'), //first level keys are language ids, second level are field names
			(Request::file('field') == null ? [] : Request::file('field')), //first level keys are language ids, second level are field names
			Auth::user()->id_user, 
			config($this->getConfigPrefix() . '.add.language_independent_fields'), 
			config($this->getConfigPrefix() . '.add.language_dependent_fields'), 
			$this->getRoutePrefix()
		);
		
		$id_item = $item->{$item->getKeyName()};
		
		$this->processGalleryImages(
			Request::input('gallery_image'), //first level keys are language ids, second level are field names
			$id_item
		);
		
		return $retval;
	}
	
	public function adminEdit($id)
	{
		$model = $this->getModel();
		$item = $model::findOrFail($id);
		
		$language_dependent_fields = config($this->getConfigPrefix() . '.edit.language_dependent_fields');
		$language_independent_fields = config($this->getConfigPrefix() . '.edit.language_independent_fields');
		
		// load images
		$languages = App::make('LanguageRepository')->getAll();
		foreach ($languages as $language)
		{
			foreach ($language_dependent_fields as $field)
			{
				if ($field['type'] != 'gallery::admin.add_fields.gallery_images') continue;
				$item->gallery_images[$language->id_language][$field['name']] = [];
			}
		}
		
		foreach ($language_independent_fields as $field)
		{
			if ($field['type'] != 'gallery::admin.add_fields.gallery_images') continue;
			$item->gallery_images[-1][$field['name']] = [];
		}
		
		foreach ($item->gallery_images as $id_language=>$fields)
		{
			foreach ($fields as $field_name=>$images)
			{
				$query = GalleryImage::where('table_name', $this->getPackageName())
						->where('column_name', $field_name)
						->where('id_row', $id);
				
				$query = ($id_language == -1 ? 
					$query->whereNull('id_language') : 
					$query->where('id_language', $id_language));
				
				$item->gallery_images[$id_language][$field_name] = $query->orderBy('ord')->get();
			}
		}
		
		return $this->admin_helper->adminEdit(
			$this->getPackageName(), 
			$this->getEditTitle(), 
			$language_dependent_fields, 
			$language_independent_fields, 
			session('messages', []), 
			$this->getRoutePrefix(), 
			$this->getModel(), 
			$item, 
			config($this->getConfigPrefix() . '.supports_preview', true)
		);
	}
	
	public function adminEditPost($id)
	{
		$is_preview = (Request::input('preview') !== null);
		
		$model = $this->getModel();
		$item = $model::findOrFail($id);
		
		$retval = $this->adminEditPostHandle(
			$is_preview, 
			$item, 
			Request::input('field'), //first level keys are language ids, second level are field names
			(Request::file('field') == null ? [] : Request::file('field')), //first level keys are language ids, second level are field names
			Auth::user()->id_user, 
			config($this->getConfigPrefix() . '.add.language_independent_fields'), 
			config($this->getConfigPrefix() . '.add.language_dependent_fields'), 
			$this->getRoutePrefix()
		);
		
		$this->processGalleryImages(
			Request::input('gallery_image'), //first level keys are language ids, second level are field names
			$id
		);
		
		return $retval;
	}
	
	public function adminDeletePost()
	{
		$model = $this->getModel();
		
		$id   = Request::input('id');
		$item = $model::findOrFail($id);
		
		GalleryImage::where('table_name', $this->getPackageName())
			->where('id_row', $id)
			->delete();
		
		$this->deleteDirectory('uploads/' . $this->getRoutePrefix() . '/' . $id);
		
		return parent::adminDeletePost();
	}
	
	private function deleteDirectory($dir)
	{
		$target_dir = realpath(trim($dir, './\\'));
		
		if (php_uname('s') == 'Windows NT')
		{
			system('rmdir /S /Q ' . escapeshellarg($target_dir));
		}
		else
		{
			system('rm -rf ' . escapeshellarg($target_dir));
		}
		
		/*
		//due to delays in deleting files/directories, this code works, but with an error:
		if (!file_exists($dir)) return true;
		if (!is_dir($dir))      return unlink($dir);
		
		foreach (scandir($dir) as $item)
		{
			if ($item == '.' || $item == '..') continue;
			if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
		}
		
		return rmdir($dir);
		*/
	}
		
	public function adminUploadGalleryFile($upload_dir)
	{
		$upload_dir = str_replace([ '..', '/', '\\' ], '-', $upload_dir);
		
		$temp_dir = '../storage/app/temp/' . $this->getRoutePrefix() . '/' . $upload_dir;
		if (!file_exists($temp_dir))
		{
			// create dir, but suppress possible errors
			// since we're checking for dir existance, errors should occur, but they do, 
			//    because of concurrent requests (request A notices this dir doesn't exist yet, 
			//    but before it can create it, request B does so; so when request A tries to create it, 
			//    it fails miserably)
			@mkdir($temp_dir, 0777, true);
		}
		
		$config = new \Flow\Config();
		$config->setTempDir($temp_dir);
		$file = new \Neonbug\Gallery\Models\FlowFile($config);
		
		if ($file->checkChunk()) {
			header('HTTP/1.1 200 Ok');
			exit();
		} else {
			header('HTTP/1.1 204 No Content');
			exit();
		}
	}
	public function adminUploadGalleryFilePost($upload_dir)
	{
		$upload_dir = str_replace([ '..', '/', '\\' ], '-', $upload_dir);
		
		$temp_dir = '../storage/app/temp/' . $this->getRoutePrefix() . '/' . $upload_dir;
		if (!file_exists($temp_dir))
		{
			// create dir, but suppress possible errors
			// since we're checking for dir existance, errors should occur, but they do, 
			//    because of concurrent requests (request A notices this dir doesn't exist yet, 
			//    but before it can create it, request B does so; so when request A tries to create it, 
			//    it fails miserably)
			@mkdir($temp_dir, 0777, true);
		}
		
		$config = new \Flow\Config();
		$config->setTempDir($temp_dir);
		$file = new \Neonbug\Gallery\Models\FlowFile($config);
		
		if ($file->validateChunk()) {
			$file->saveChunk();
		} else {
			// error, invalid chunk upload request, retry
			header('HTTP/1.1 400 Bad Request');
			exit();
		}
		
		if ($file->validateFile()) {
			// file upload was completed
			
			$dir = 'uploads/' . $this->getRoutePrefix() . '/temp/' . $upload_dir;
			$filename = str_replace([ '..', '/', '\\' ], '-', $file->getFileName()); //very basic filename validation
			
			if (!file_exists($dir))
			{
				// create dir, but suppress possible errors
				// since we're checking for dir existance, errors should occur, but they do, 
				//    because of concurrent requests (request A notices this dir doesn't exist yet, 
				//    but before it can create it, request B does so; so when request A tries to create it, 
				//    it fails miserably)
				@mkdir($dir, 0777, true);
			}
			
			if (!$file->save($dir . '/' . $filename))
			{
				//TODO throw an error or sth
			}
		} else {
			// this is not a final chunk, continue to upload
		}
	}
	
	public function processGalleryImages($gallery_images, $id_item)
	{
		$c = 1;
		foreach ($gallery_images as $id_language=>$fields)
		{
			foreach ($fields as $field_name=>$images)
			{
				// existing images
				$query = GalleryImage::where('table_name', $this->getPackageName())
						->where('column_name', $field_name)
						->where('id_row', $id_item);
				
				$query = ($id_language == -1 ? 
					$query->whereNull('id_language') : 
					$query->where('id_language', $id_language));
				
				$existing_images = $query->get();
				$existing_image_names = [];
				$existing_image_name_to_objs = [];
				foreach ($existing_images as $image)
				{
					$existing_image_names[]                     = $image->image;
					$existing_image_name_to_objs[$image->image] = $image;
				}
				
				// process
				$field_name_clean = str_replace([ '..', '/', '\\' ], '-', $field_name);
				
				foreach ($images['images'] as $image)
				{
					$image_filename = $image;
					if (stripos($image_filename, '/') !== false)
					{
						$arr = explode('/', $image_filename);
						if (sizeof($arr) != 2 || !is_numeric($arr[0])) continue;
						
						$upload_dir_clean = str_replace([ '..', '/', '\\' ], '-', $arr[0]);
						$image_filename   = str_replace([ '..', '/', '\\' ], '-', $arr[1]);
						
						$file_path = 'uploads/' . $this->getRoutePrefix() . '/temp/' . $upload_dir_clean . '/' . 
							$image_filename;
						
						$destination_dir = 'uploads/' . $this->getRoutePrefix() . '/' . $id_item . '/' . 
							($id_language == -1 ? 0 : $id_language) . '/' . $field_name_clean;
						$destination_path = $destination_dir . '/' . $image_filename;
						
						if (!file_exists($destination_dir))
						{
							mkdir($destination_dir, 0777, true);
						}
						rename($file_path, $destination_path);
						
						$image_item = new GalleryImage();
						$image_item->id_language = ($id_language == -1 ? null : $id_language);
						$image_item->table_name  = $this->getPackageName();
						$image_item->column_name = $field_name;
						$image_item->id_row      = $id_item;
						$image_item->image       = $image_filename;
						$image_item->ord         = $c++;
						$image_item->save();
					}
					else if (array_key_exists($image_filename, $existing_image_name_to_objs))
					{
						$image_item = $existing_image_name_to_objs[$image_filename];
						$image_item->ord = $c++;
						$image_item->save();
						
						unset($existing_image_name_to_objs[$image_filename]);
					}
				}
				
				// delete missing images
				foreach ($existing_image_name_to_objs as $name=>$image_item)
				{
					$filename = 'uploads/' . $this->getRoutePrefix() . '/' . $id_item . '/' . 
						($id_language == -1 ? 0 : $id_language) . '/' . $field_name_clean . '/' . $name;
					if (file_exists($filename)) unlink($filename);
					
					GalleryImage::where($image_item->getKeyName(), $image_item->id_gallery_image)
						->delete();
				}
			}
		}
	}
	
}
