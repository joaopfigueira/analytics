<?php

class PiwikTest extends PHPUnit_Framework_TestCase
{
	protected $url = 'http://www.anywebsite.com';
	protected $token_auth = '123456789';
	protected $idSite = 10;

	/*
	* Test if getUrl() returns a URL as expected
	*
	* @test
	*/
	public function testGetUrlreturnsExpectedUrl()
	{
		$scope = array ('scopeone'=>'one', 'scopetwo'=>'two', 'scopethree'=>'three');

		$piwik 		= new \Analytics\DataSources\Piwik($this->url, $this->token_auth, $this->idSite);
		$result 	= $piwik->getUrl($scope);
		$expected 	= "http://www.anywebsite.com/?token_auth=123456789&idSite=10&scopeone=one&scopetwo=two&scopethree=three";

		$this->assertEquals($result, $expected);
	}
}