<?php

namespace EasyCSV\Tests;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
    private $reader;

    public function setUp()
    {
        $this->reader = new \EasyCSV\Reader(__DIR__ . '/read.csv');
    }

    public function testOneAtAtime()
    {
    	$expected = new \stdClass;
    	$expected->column1 = '1column2value';
    	$expected->column2 = '1column3value';
    	$expected->column3 = '1column4value';

        $row = $this->reader->getRow();
        $this->assertEquals($expected, $row);
    }

    public function testGetAll()
    {
        $this->assertCount(5, $this->reader->getAll());
    }
}