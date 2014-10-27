<?php namespace Analytics;

use Analytics\DataSources\DataSourcesInterface;

/**
 * Analytics
 *
 * Copyright (c) 2014 João Paulo Figueira <joao.figueira@webnation.pt>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of João Paulo Figueira nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    	Analytics
 * @author 		João Paulo Figueira <joao.figueira@webnation.pt>
 * @copyright  	2014 João Paulo Figueira <joao.figueira@webnation.pt>
 * @license    	http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License 
 */

/**
 * Constructs an Object, passes it to a analytics HTTP API client using GET method.
 *
 * @package    	Analytics
 * @author 		João Paulo Figueira <joao.figueira@webnation.pt>
 * @copyright  	2014 João Paulo Figueira <joao.figueira@webnation.pt>
 * @license    	http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License 
 */
abstract class Analytics
{
	protected $url;
	protected $token_auth;
	protected $idSite = 1;
	protected $period = 'day';
	protected $date = 'yesterday';
	protected $format = 'PHP';
	protected $method;
	protected $module = 'API';
	protected $driver; // driver injection

	/**
	 * Contructs the object with essential values
	 *
	 * @param object $driver		Object that will process the request
	 * @param string $url
	 * @param string $token_auth
	 * @param integer $idSite
	 * @return object $this
	 */
	public function __construct(DataSourcesInterface $driver, $url, $token_auth, $idSite = 1)
	{
		$this->driver = $driver;
		$this->url = $url;
		$this->token_auth = $token_auth;
		$this->idSite = $idSite;
		return $this;
	}

	/**
	 * @param string $period 		Valid: day, week, month, year, range
	 * @return object $this
	 */
	public function period($period)
	{
		$period = strtolower($period); //corrects a stupid bug
		$acceptable = array('day', 'week', 'month', 'year', 'range');
		if (in_array($period, $acceptable)){
			$this->period = $period;
		} else {
			$this->period = 'day';
		}
		return $this;
	}	

	/**
	 * @param string $startDate 	Should be: YYYY-MM-DD
	 * @param string $endDate		Should be: YYYY-MM-DD
	 * @return object $this
	 */
	public function date($startDate, $endDate = null)
	{
		if (!empty($endDate)){
			$this->date = $startDate.','.$endDate;
		} else {
			$this->date = $startDate;			
		}
		return $this;
	}

	/**
	 * @param string $format		Valid: XML, JSON, CSV, TSV, HTML, PHP, RSS
	 * @return object $this
	 */
	public function format($format)
	{
		$format = strtoupper($format); //bugfix
		$acceptable = array('XML', 'JSON', 'CSV', 'TSV', 'HTML', 'PHP', 'RSS');
		if (in_array($format, $acceptable))
		{
			$this->format = $format;
		} else {
			$this->format = 'PHP';
		}
		return $this;
	}

	/**
	 * @param string $method 		Sets API method
	 * @return object $this
	 */
	public function method($method)
	{
		$this->method = $method;
		return $this;
	}

	/**
	 * Returns the query result from the API
	 *
	 * @param array $addedData
	 * @return array $driver->get()		
	 */
	public function get($addedData=array())
	{
		$data = array (
			'url'			=> $this->url,
			'token_auth'	=> $this->token_auth,
			'idSite'		=> $this->idSite,
			'period'		=> $this->period,
			'date'			=> $this->date,
			'format'		=> $this->format,
			'method'		=> $this->method,
			'module'		=> $this->module
		);

		return $this->driver->get(array_merge($data, $addedData));
	}

	/**
	 * Returns the query result from the API
	 *
	 * @param array $addedData
	 * @return string $driver->getUrl()
	 */
	public function getUrl($addedData=array())
	{
		$data = array (
			'url'			=> $this->url,
			'token_auth'	=> $this->token_auth,
			'idSite'		=> $this->idSite,
			'period'		=> $this->period,
			'date'			=> $this->date,
			'format'		=> $this->format,
			'method'		=> $this->method,
			'module'		=> $this->module
		);

		return $this->driver->getUrl(array_merge($data, $addedData));		
	}	
}
