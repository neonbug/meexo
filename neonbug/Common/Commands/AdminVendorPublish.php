<?php namespace Neonbug\Common\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Neonbug\Common\Providers\BaseServiceProvider;
use Illuminate\Filesystem\Filesystem;

class AdminVendorPublish extends \Illuminate\Foundation\Console\VendorPublishCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'vendor:publish-admin';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Publish any publishable admin assets from vendor packages';

	/**
	 * Create a new command instance.
	 *
	 * @param  \Illuminate\Filesystem\Filesystem
	 * @return void
	 */
	public function __construct(Filesystem $files)
	{
		parent::__construct($files);
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$paths = BaseServiceProvider::pathsToPublishAdmin(
			$this->option('provider'), $this->option('tag')
		);

		if (empty($paths))
		{
			return $this->comment("Nothing to publish.");
		}

		foreach ($paths as $from => $to)
		{
			if ($this->files->isFile($from))
			{
				$this->publishFile($from, $to);
			}
			elseif ($this->files->isDirectory($from))
			{
				$this->publishDirectory($from, $to);
			}
			else
			{
				$this->error("Can't locate path: <{$from}>");
			}
		}

		$this->info('Publishing Complete!');
	}

}
