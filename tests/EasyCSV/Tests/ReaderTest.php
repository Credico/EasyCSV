<?php

namespace EasyCSV\Tests;

use EasyCSV\Reader;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testOneAtAtime()
    {
    	$this->reader = new Reader(__DIR__ . '/read.csv');
    	$expected = array(
    		'column1' => '1column2value',
    		'column2' => '1column3value',
    		'column3' => '1column4value',
    	);

        $row = $this->reader->getRow();
        $this->assertEquals($expected, $row);
    }

    public function testGetAll()
    {
    	$this->reader = new Reader(__DIR__ . '/read.csv');
        $this->assertCount(5, $this->reader->getAll());
    }

    /** 
     * @test
     * @expectedException EasyCSV\MalformedCsvException
     */
	public function FailOnMalformedCsv()
	{
		$this->reader = new Reader(__DIR__ . '/tooManyColumns.csv');
		$this->reader->setDebug(true);
		$this->reader->getAll();
	} 
	
    /** 
     * @test
     */
	public function MalformedCsvIsCleanedUp()
	{
		$this->reader = new Reader(__DIR__ . '/tooManyColumns.csv');
		$this->reader->setDebug(false);
		$rows = $this->reader->getAll();
		$this->assertCount(5, $rows);
	} 
}
