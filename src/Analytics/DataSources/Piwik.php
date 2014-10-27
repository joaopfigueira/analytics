<?php namespace Analytics\DataSources;
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
 * @subpackage 	DataSources
 * @author 		João Paulo Figueira <joao.figueira@webnation.pt>
 * @copyright  	2014 João Paulo Figueira <joao.figueira@webnation.pt>
 * @license    	http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License 
 */

/**
 * sends a GET request to Piwik API, catches the result contents.
 *
 * @package    	Analytics
 * @subpackage 	DataSources 
 * @author 		João Paulo Figueira <joao.figueira@webnation.pt>
 * @copyright  	2014 João Paulo Figueira <joao.figueira@webnation.pt>
 * @license    	http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License 
 */
class Piwik implements DataSourcesInterface
{
	/**
	 * @param  array $scope
	 * @return array $content
	 */
	public function get($scope)
	{
		$url = $scope['url'].'/?';
		unset($scope['url']);

		$request = $url . http_build_query($scope);

		$fetched = file_get_contents($request);
		$content = $this->is_serialized($fetched) ? unserialize($fetched) : $fetched;

		return $content;
	}

	public function getUrl($scope)
	{
		$url = $scope['url'].'/?';
		unset($scope['url']);

		$request = $url . http_build_query($scope);

		return $request;
	}	

	/**
	 * Tests if an input is valid PHP serialized string.
	 *
	 * Checks if a string is serialized using quick string manipulation
	 * to throw out obviously incorrect strings. Unserialize is then run
	 * on the string to perform the final verification.
	 *
	 * Valid serialized forms are the following:
	 * <ul>
	 * <li>boolean: <code>b:1;</code></li>
	 * <li>integer: <code>i:1;</code></li>
	 * <li>double: <code>d:0.2;</code></li>
	 * <li>string: <code>s:4:"test";</code></li>
	 * <li>array: <code>a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}</code></li>
	 * <li>object: <code>O:8:"stdClass":0:{}</code></li>
	 * <li>null: <code>N;</code></li>
	 * </ul>
	 *
	 * @author		Chris Smith <code+php@chris.cs278.org>
	 * @copyright	Copyright (c) 2009 Chris Smith (http://www.cs278.org/)
	 * @license		http://sam.zoy.org/wtfpl/ WTFPL
	 * @param		string	$value	Value to test for serialized form
	 * @param		mixed	$result	Result of unserialize() of the $value
	 * @return		boolean			True if $value is serialized data, otherwise false
	 */
	private function is_serialized($value, &$result = null)
	{
		// Bit of a give away this one
		if (!is_string($value))
		{
			return false;
		}
	 
		// Serialized false, return true. unserialize() returns false on an
		// invalid string or it could return false if the string is serialized
		// false, eliminate that possibility.
		if ($value === 'b:0;')
		{
			$result = false;
			return true;
		}
	 
		$length	= strlen($value);
		$end	= '';
	 
		switch ($value[0])
		{
			case 's':
				if ($value[$length - 2] !== '"')
				{
					return false;
				}
			case 'b':
			case 'i':
			case 'd':
				// This looks odd but it is quicker than isset()ing
				$end .= ';';
			case 'a':
			case 'O':
				$end .= '}';
	 
				if ($value[1] !== ':')
				{
					return false;
				}
	 
				switch ($value[2])
				{
					case 0:
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					case 9:
					break;
	 
					default:
						return false;
				}
			case 'N':
				$end .= ';';
	 
				if ($value[$length - 1] !== $end[0])
				{
					return false;
				}
			break;
	 
			default:
				return false;
		}
	 
		if (($result = @unserialize($value)) === false)
		{
			$result = null;
			return false;
		}
		return true;
	}	
}