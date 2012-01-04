<?php

namespace EasyCSV;

class Reader extends AbstractBase
{
	private $_headers;
	private $_line;

	public function __construct($path, $delimiter = ',', $mode = 'r+')
	{
		parent::__construct($path, $delimiter, $mode);
		$this->_line    = 0;
	}

	public function getRow()
	{
		if (($row = fgetcsv($this->_handle, 4096, $this->_delimiter, $this->_enclosure)) !== false) {
			$row = array_map('trim', $row);
			$this->_line++;
			if (empty($this->_headers)) {
				$this->_headers = $row;
				return $this->getRow();
			} else {
				$result = array_combine($this->_headers, $row);
				foreach($this->_headers as $header) {
					if(empty($header)) {
						unset($result[$header]);
					}
				}
				return $result;
			}
		} else {
			return false;
		}
	}

	public function getAll()
	{
		$data = array();
		while ($row = $this->getRow()) {
			$data[] = $row;
		}
		return $data;
	}

	public function getLineNumber()
	{
		return $this->_line;
	}
}