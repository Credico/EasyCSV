<?php

namespace EasyCSV\Tests;

use EasyCSV\Reader;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
	/**
	* @test
	*/
    public function GetsARow()
    {
    	$reader = new Reader(__DIR__.'/read.csv', array('col1', 'col2', 'col3'));
    	$expected = new \stdClass;
    	$expected->col1 = 'A1';
    	$expected->col2 = 'A2';
    	$expected->col3 = 'A3';

        $this->assertEquals($expected, $reader->getRow());
    }

    /**
    * @test
    */
    public function GetsAllRows()
    {
    	$reader = new Reader(__DIR__.'/read.csv', array('col1', 'col2', 'col3'));
        $this->assertCount(5, $reader->getAll());
    }

    /** 
     * @test
     * @expectedException EasyCSV\MalformedCsvException
     */
	public function FailsOnMalformedCsv()
	{
		$reader = new Reader(__DIR__ . '/tooManyColumns.csv', array('col1', 'col2', 'col3'));
		$reader->setDebug(true);
		$reader->getAll();
	} 
	
    /** 
     * @test
     */
	public function CleansUpMalformedCsv()
	{
		$reader = new Reader(__DIR__ . '/tooManyColumns.csv', array('col1', 'col2', 'col3'));
		$reader->setDebug(false);
		$this->assertCount(5, $reader->getAll());
	}

	/** @test */
	public function HeaderlessCsv()
	{
		$reader = new Reader(__DIR__.'/read.csv', array('col1', 'col2', 'col3'), ',', false);
		$this->assertCount(6, $reader->getAll());
	}
}
