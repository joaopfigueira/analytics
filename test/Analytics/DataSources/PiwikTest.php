<?php

class PiwikTest extends PHPUnit_Framework_TestCase
{
	/*
	* Test if getUrl() returns a URL as expected
	*
	* @test
	*/
	public function testGetUrlreturnsExpectedUrl()
	{
		$scope = array ('url'=>'http://www.anywebsite.com', 'scopeone'=>'one', 'scopetwo'=>'two', 'scopethree'=>'three');

		$piwik 		= new \Analytics\DataSources\Piwik;
		$result 	= $piwik->getUrl($scope);
		$expected 	= "http://www.anywebsite.com/?scopeone=one&scopetwo=two&scopethree=three";

		$this->assertEquals($result, $expected);
	}

	/*
	* Test if get() returns the content of the URL
	*
	* @test
	*/
	public function testGetReturnsTheCorrectHtml()
	{
		$scope = array ('url'=>'http://getbootstrap.com/examples/starter-template');

		$piwik 		= new \Analytics\DataSources\Piwik;
		$result 	= $piwik->get($scope);

		$expected = file_get_contents('http://getbootstrap.com/examples/starter-template');

		$this->assertEquals($result, $expected);
	}
}