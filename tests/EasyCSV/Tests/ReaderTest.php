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
		$reader = new Reader(__DIR__.'/read.csv', array('col1', 'col2', 'col3'), ',', Reader::FIRST_LINE_IS_DATA);
		$this->assertCount(6, $reader->getAll());
	}
	
	/** @test */
	public function FixedWidth()
	{
		$widths = array(15, 10);
		$reader = new Reader(__DIR__.'/fixedWidth.csv', array('col1', 'col2'), ';', Reader::FIRST_LINE_IS_HEADER, $widths);
		
		$expected = new \stdClass;
    	$expected->col1 = '123456789012345';
    	$expected->col2 = '1234567890';
        $this->assertEquals($expected, $reader->getRow());

        $expected = new \stdClass;
    	$expected->col1 = 'a1;a2';
    	$expected->col2 = '1234567890';
        $this->assertEquals($expected, $reader->getRow());
		
        $expected = new \stdClass;
    	$expected->col1 = '123456';
    	$expected->col2 = 'b1;b2;b3';
        $this->assertEquals($expected, $reader->getRow());
	}

    /**
     * @test
     * @expectedException \EasyCsv\FilePermissionException
     */
    public function ReadOnlyFile()
    {
        chmod(__DIR__.'/readonly.csv',0 );
        new Reader(__DIR__.'/readonly.csv', array('col1', 'col2', 'col3'), ',', Reader::FIRST_LINE_IS_DATA);
    }
}
