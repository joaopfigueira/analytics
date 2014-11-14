<?php

use Analytics\Querys\BaseQuery as Analytics;

class AnalyticsTest extends PHPUnit_Framework_TestCase
{
	/**
	* Test if get() returns the correct API data
	*
	* @test
	*/	
	public function testGetReturnsCorrectAPIdata()
	{
		$apiResponse = array(array('nb_visits'=>10, 'nb_hits'=>20), array('nb_visits'=>20, 'nb_hits'=>30));

        $driver = $this->getMockBuilder('\Analytics\DataSources\Piwik')
        	->disableOriginalConstructor()
            ->getMock();

		$driver->expects($this->once())
		    ->method('get')
		    ->will($this->returnValue($apiResponse));   

		$analytics = new Analytics($driver);
		$result = $analytics->get();

		$this->assertEquals($apiResponse, $result);
	}

	/**
	* Test if getUrl returns the correct URL
	*
	* @test
	*/
	public function testGetUrlReturnsTheCorrectURL()
	{
		$apiResponse = 'http://www.somesite.com/?val1=one&val2=two&val3=three';

        $driver = $this->getMockBuilder('\Analytics\DataSources\Piwik')
        	->disableOriginalConstructor()
            ->getMock();

		$driver->expects($this->once())
		    ->method('getUrl')
		    ->will($this->returnValue($apiResponse));  

		$analytics = new Analytics($driver);
		$result = $analytics->getUrl();

		$this->assertEquals($apiResponse, $result);
	}

	public function providerForPeriodInsertions()
	{
		return array(
			array('day', 'day'),		
			array('week', 'week'),
			array('Week', 'week'),
			array('WEEK', 'week'),
			array('month', 'month'),
			array('Month', 'month'),
			array('MONTH', 'month'),
			array('year', 'year'),
			array('Year', 'year'),
			array('YEAR', 'year'),		
			array('range', 'range'),
			array('Range', 'range'),
			array('RANGE', 'range'),
			array('oranges', 'day'),
			array('apples', 'day'),
			);
	}

	/**
	* Test if Period() Accepts and sets correctly period property
	*
    * @param string $originalString 
    * @param string $expectedResult 
    *
    * @dataProvider providerForPeriodInsertions
    */	
	public function testIfPeriodAcceptsTheCorrectParameters($originalString, $expectedResult)
	{
        $driver = $this->getMockBuilder('\Analytics\DataSources\Piwik')
        	->disableOriginalConstructor()
            ->getMock();		

		$analytics = new Analytics($driver);
		$analytics->period($originalString);

		$propertyValue = $this->readAttribute($analytics, "period");
		$this->assertEquals($propertyValue, $expectedResult);
	}

	public function providerForFormatInsertions()
	{
		return array(
			array('xml', 'XML'),
			array('json', 'JSON'),
			array('csv', 'CSV'),
			array('tsv', 'TSV'),
			array('html', 'HTML'),
			array('php', 'PHP'),
			array('rss', 'RSS'),
			array('apples', 'PHP'),
			array('oranges', 'PHP'),
			);
	}	

	/**
	* Test if format() Accepts and sets correctly format property
	*
    * @param string $originalString 
    * @param string $expectedResult 
    *
    * @dataProvider providerForFormatInsertions
    */	
	public function testIfFormatAcceptsTheCorrectParameters($originalString, $expectedResult)
	{
        $driver = $this->getMockBuilder('\Analytics\DataSources\Piwik')
        	->disableOriginalConstructor()
            ->getMock();		

		$analytics = new Analytics($driver);

		// All Caps
		$originalString = strtoupper($originalString);
		$analytics->format($originalString);
		$propertyValue = $this->readAttribute($analytics, "format");
		$this->assertEquals($propertyValue, $expectedResult);

		// All lowercase
		$originalString = strtolower($originalString);
		$analytics->format($originalString);
		$propertyValue = $this->readAttribute($analytics, "format");
		$this->assertEquals($propertyValue, $expectedResult);

		// Uppercase First chr
		$originalString = ucfirst($originalString);
		$analytics->format($originalString);
		$propertyValue = $this->readAttribute($analytics, "format");
		$this->assertEquals($propertyValue, $expectedResult);	
	}
}