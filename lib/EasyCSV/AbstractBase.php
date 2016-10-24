<?php

namespace EasyCSV;

abstract class AbstractBase
{
	protected $_handle;
	protected $delimiter = ',';
	protected $enclosure = '"';

	public function __construct($path, $delimiter = ',', $mode = 'r+')
	{
		$this->delimiter = $delimiter;
		$this->_handle = @fopen($path, $mode);
        if( !$this->_handle ) {
            throw new FilePermissionException('You have insufficient rights to open the file ' . $path . ' in mode ' . $mode);
        }
	}

	public function __destruct()
	{
		if (is_resource($this->_handle)) {
			fclose($this->_handle);
		}
	}

}
