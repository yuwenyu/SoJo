<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Service;

interface CommonServiceInterface
{	
	/**
	 * 
	 * @param object 						$oMSource
	 * @param array 						$fields
	 * @param string|array|object|callable 	$callback PHP callback $callback
	 * @param array 						$metaData
	 */
	public function isSourceExistByFields($oMSource, array $fields, $callback, array $metaData);
	
	/**
	 * 
	 * @param object 						$oSource
	 * @param object 						$oMSource
	 * @param string|array|object|callable 	$callback PHP callback $callback
	 * @param array 						$metaData
	 */
	public function insSourceData($oSource, $oMSource, $callback, array $metaData);
	
	/**
	 * 
	 * @param string 						$sStaticIdentity
	 * @param string|array|object|callable 	$callback PHP callback $callback
	 * @param array 						$metaData
	 * @param boolean 						$cache
	 */
	public function getSourceData($sStaticIdentity, $callback, array $metaData, $cache);
	
	/**
	 * Validator Source Data
	 *
	 * @param object 	$request
	 * @param object 	$oSource
	 * @param object 	$oFSource
	 * @param boolean 	$method
	 * @param string 	$validator
	 */
	public function vdrSourceData($request, $oSource, $oFSource, $method, $validator);
	
	/**
	 * Module of Error Message
	 * 
	 * @param integer $code
	 * @param array $data
	 * @param array $msg
	 * 
	 * @author WenYu
	 * @return array
	 */
	public function getErrorMsg($code, $data, $msg);
	
	/**
	 * Module of Success Message
	 * 
	 * @param array $data
	 * @param array $msg
	 * 
	 * @author WenYu
	 * @return array
	 */
	public function getSuccessMsg($data, $msg);
}