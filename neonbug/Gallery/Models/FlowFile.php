<?php namespace Neonbug\Gallery\Models;

class FlowFile extends \Flow\File {
	
	public function getFileName()
	{
		return $this->request->getFileName();
	}
	
}
