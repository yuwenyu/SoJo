<?php
/**
 * Self::Framework by Zend
*
* @author WenYu
* @copyright Copyright (c) 2015~2***
*/

namespace Administrator\Service;

interface CoreServiceInterface
{	
/**
 * Db Adapter
 * 
 * @return Object
 */
	public function getDbAdapter();
	
/**
 * $iterator is Iterator or Not
 * 
 * @param Traversable $iterator
 * 
 * @author Wen
 * @return boolean
 */
	public function isIterator($iterator);
	
/**
 * $iterator is Iterator or Not
 * 
 * @param Traversable $iterator
 * 
 * @author Wen
 * @return boolean
 */
	public function getSourceMsg($iterator);
	
/**
 * @author Wen
 * @return array 
 */
	public function getCurrentConfiguration();
	

/**
 * @author Wen
 * @return array
 */
	public function getToRouteConfiguration();
	
/**
 * 
 * @param Iterator $iterator
 * @return array
 */
	public function getStaticParameters($iterator);
	
/**
 * 
 * @param string $key
 * 
 * @author Wen
 * @return array
 */
	public function getCacheItemByKey($key);
	
/**
 * Set the Method of Adapter
 * 
 * @param string $adapter
 * 
 * @author Wen
 * @return Object
 */
	public function setAdapterMethod($adapter);
	
/**
 * Get the Method of Adapter
 * 
 * @author Wen
 * @return string
 */
	public function getAdapterMethod();
	
/**
 * Attain the Adapter Object By Type of File & Redis
 * 
 * @author Wen
 * @return Object
 */
	public function getCacheAdapter();

/**
 * 
 * @param array $iterator
 * @return Iterator
 */
	public function ArrayToIterator($iterator);
	
/**
 * 
 * @param Iterator $iterator
 * @return array
 */
	public function IteratorToArray($iterator);
	
/**
 * 
 * @param Iterator $sourceData
 * @param string $source
 * @param string $alias
 * 
 * @return array
 */
	public function HasManyToArray($sourceData, $source, $alias);

/**
 * 
 * @param string|array|object|callable $callback PHP callback
 * @param array                        $metadata  Callback metadata
 */
	public function CallbackHandler($callback, array $metaData);
}