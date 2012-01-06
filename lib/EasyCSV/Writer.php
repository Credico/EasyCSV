<?php

namespace EasyCSV;

class Writer extends AbstractBase
{
	private $line = 0;
	
	public function __construct($path, $delimiter = ',')
	{
		parent::__construct($path, $delimiter, 'w+');
	}

	public function writeRow($row)
	{
		if (is_string($row)) {
			$row = explode(',', $row);
			$row = array_map('trim', $row);
		}
		$row = array_map(function($key) {
			return mb_check_encoding($key, 'UTF-8') ? $key : utf8_encode($key);
		}, $row);
		if ($this->line == 0) {
			fputcsv($this->_handle, array_keys($row), $this->delimiter, $this->enclosure);
		}
		$this->line++;
		return fputcsv($this->_handle, $row, $this->delimiter, $this->enclosure);
	}

	public function writeFromArray(array $array)
	{
		foreach ($array as $key => $value) {
			$this->writeRow($value);
		}
	}

	public function getLineCount()
	{
		return $this->line;
	}
}