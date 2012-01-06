<?php

namespace EasyCSV;

class Reader extends AbstractBase
{
	private $headers;
	private $line = 0;
	private $debug;

	public function __construct($path, array $headers, $delimiter = ',', $firstLineIsHeader = true)
	{
		parent::__construct($path, $delimiter, 'r+');
		$this->headers = $headers;
		if($firstLineIsHeader) {
			$this->fetchRow();
		}

	}

	public function setDebug($bool)
	{
		$this->debug = $bool;
	}

	/** @return array */
	private function fetchRow()
	{
		$row = fgetcsv($this->_handle, 4096, $this->delimiter, $this->enclosure);
		if($row !== false) {
			$row = array_map('trim', $row);
			$this->line++;
		}
		return $row;
	}

	private function mapToHeaders(array $row)
	{
		if(count($this->headers) != count($row)) {
			if($this->debug) {
				throw new MalformedCsvException(sprintf("Line %s has more columns than the amount of headers", $this->line));
			} else {
				$row = array_splice($row, 0, count($this->headers)); // cut the rows if there's too many
			}
		}
		return (object) array_combine($this->headers, $row);
	}

	public function getRow()
	{
		if (($row = $this->fetchRow()) !== false) {
			$row = $this->mapToHeaders($row);
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
		return $this->line;
	}
}