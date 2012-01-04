<?php

namespace EasyCSV;

class Reader extends AbstractBase
{
	private $_headers;
	private $_line = 0;
	private $debug;

	public function __construct($path, $delimiter = ',', $mode = 'r+')
	{
		parent::__construct($path, $delimiter, $mode);
		$this->_headers = $this->fetchRow();

	}

	public function setDebug($bool)
	{
		$this->debug = $bool;
	}

	private function fetchRow()
	{
		$row = fgetcsv($this->_handle, 4096, $this->_delimiter, $this->_enclosure);
		if($row !== false) {
			$row = array_map('trim', $row);
			$this->_line++;
		}
		return $row;
	}

	private function mapToHeaders(array $row)
	{
		if(count($this->_headers) != count($row)) {
			if($this->debug) {
				throw new MalformedCsvException("The number of columns doesn't match");
			} else {
				$row = array_splice($row, 0, count($this->_headers)); // cut the rows if there's too many
			}
		}
		return array_combine($this->_headers, $row);
	}

	private function removeEmptyColumnHeaders(array $row)
	{
		foreach($this->_headers as $header) {
			// remove columns with empty headers
			if(empty($header)) {
				unset($row[$header]);
			}
		}
		return $row;
	}

	public function getRow()
	{
		if (($row = $this->fetchRow()) !== false) {
			$row = $this->mapToHeaders($row);
			$row = $this->removeEmptyColumnHeaders($row);
		}
		return $row;
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