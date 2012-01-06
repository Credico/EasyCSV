<?php

namespace EasyCSV\Tests;

class WriterTest extends \PHPUnit_Framework_TestCase
{
    private $writer;

    public function setUp()
    {
        $this->writer = new \EasyCSV\Writer(__DIR__ . '/write.csv');

        $this->writer->writeRow('column1, column2, column3');
        $this->writer->writeFromArray(array(
            '1test1, 1test2ing this out, 1test3',
            array('2test1', '2test2 ing this out ok', '2test3')
        ));
    }

    public function testReadWrittenFile()
    {
        $reader = new \EasyCSV\Reader(__DIR__ . '/write.csv', array('col1', 'col2', 'col3'));
        $results = $reader->getAll();
        
        $this->assertCount(3, $results);
    }
}