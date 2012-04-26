<?php

namespace EasyCSV;

class Reader extends AbstractBase
{
	private $headers;
	private $line = 0;
	private $debug;
	private $fixedWidths;

	/**
	 * @param string 	$path 		Path to the CSV file
	 * @param array 	$headers	Column titles, used as the property names of the resulting objects
	 * @param string 	$delimiter	Csv column separator
	 * @param boolean	$firstLineIsHeader Ignore the first line, defaults to true
	 * @param array		$fixedWidths Array of integers indicating the length of each column if the the csv is fixed-width. Defaults to null.
	 */
	public function __construct($path, array $headers, $delimiter = ',', $firstLineIsHeader = true, array $fixedWidths = null)
	{
		parent::__construct($path, $delimiter, 'r+');
		
		if(is_array($fixedWidths) && count($headers) != count($fixedWidths) ) {
			throw new \Exception("The number of headers doesn't match the number of fixed width columns");
		}
		$this->headers = $headers;
		$this->fixedWidths = $fixedWidths;
		
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
		if($this->fixedWidths) 
		{
			$row = false;
			if(false !== $record = fgets($this->_handle)) {
				$row = array();
				$offset = 0;
				foreach($this->fixedWidths as $width) {
					$row[] = substr($record, $offset, $width);
					$offset += $width + strlen($this->delimiter);
				}
			}
		} else {
			$row = fgetcsv($this->_handle, 4096, $this->delimiter, $this->enclosure);
		}
		
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
				throw new MalformedCsvException(sprintf("Line %s has more columns than the amount of headers", $this->getLineNumber()));
			} else {
				$row = array_splice($row, 0, count($this->headers)); // cut the rows if there's too many
			}
		}
		return (object) array_combine($this->headers, $row);
	}

	public function getRow()
	{
		if (($row = $this->fetchRow()) !== false) {
			$row = $this->mapToHeaders(array_map( function($key) {
				return mb_check_encoding($key, 'UTF-8') ? $key : utf8_encode($key);
			}, $row));
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