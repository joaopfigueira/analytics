<?php namespace Analytics\Querys;
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
 * @subpackage 	Querys
 * @author 		João Paulo Figueira <joao.figueira@webnation.pt>
 * @copyright  	2014 João Paulo Figueira <joao.figueira@webnation.pt>
 * @license    	http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License 
 */

use Analytics\Analytics;

/**
 * Query by operating system.
 *
 * @package    	Analytics
 * @subpackage 	Querys 
 * @author 		João Paulo Figueira <joao.figueira@webnation.pt>
 * @copyright  	2014 João Paulo Figueira <joao.figueira@webnation.pt>
 * @license    	http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License 
 */
class SO extends Analytics implements FetchInterface, DatesInterface
{
	use DatesTrait;
	
	/**
	 * @param array $extendedOptions
	 *	
	 * @return array $result
	 */
	public function fetchData($extendedOptions=array())
	{
		$baseOptions = array('filter_limit'=>5, 'expanded'=>1, 'filter_sort_column'=>'nb_visits');

		$result = $this ->period('range')
						->date($this->startDate, $this->endDate)
						->method('UserSettings.getOS')
						->get(array_merge($baseOptions, $extendedOptions));
		return $result;
	}

	/**
	 * @param integer $width
	 * @param integer $height 
	 *
	 * @return image/PNG URL $result
	 */
	public function fetchGraph($width=750, $height=350)
	{
		$result = $this ->period('range')
						->date($this->startDate, $this->endDate)
						->method('ImageGraph.get')
						->getUrl(array(
							'apiModule'=>'UserSettings',
							'apiAction'=>'getOS',
							'filter_limit'=>5,
							'expanded'=>1,							
							'graphType'=>'horizontalBar',
							'filter_sort_column'=>'nb_visits',
							'width'=>$width,
							'height'=>$height,
							'language'=>'pt'
						));
		return $result;		
	}
}