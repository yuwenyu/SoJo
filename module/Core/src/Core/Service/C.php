<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Core\Service;

use Traversable;
use ArrayIterator;

use Zend\Db\Adapter\Adapter;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\ArrayObject;
use Zend\Stdlib\CallbackHandler;
use Zend\Form\Annotation\Object;

class C
{
/**
 * 
 * @var StringTrim
 */
	private $str;
	
/**
 * 
 * @var array
 */
	private $arr;
	
/**
 * 
 * @var Iterator instanceof Traversable
 */
	private $iterator;
	
/**
 * 
 * @var db adapter
 */
	private $__c_db_adapter;

/**
 * File
 *
 * @var Filesystem
 */
	private $__c_f;
	
/**
 * Redis
 *
 * @var Redis
 */
	private $__c_r;
	
/**
 * Log Path
 * 
 * @var String
 */
	private $__c_path = './data/log';
	
/**
 * 
 * @var string
 */
	private $__c_cache_adapter;

/**
 *
 * @var string
 */
	private $__c_cache_adapter_method = '';
	
/**
 * 
 * @var array
 */
	public $__c_arr_adapter = array('F'=>'__c_f','R'=>'__c_r');
	
	public function __construct($aCacheAdapter, Adapter $oAdapter)
	{
		$this->__c_db_adapter = $oAdapter;
		$this->__c_r = $aCacheAdapter[$this->__c_arr_adapter['R']];
		$this->__c_f = $aCacheAdapter[$this->__c_arr_adapter['F']];
	}
	
/**
 * DB Adapter
 * 
 * @return Object
 */
	public function __get_db_adapter()
	{
		return $this->__c_db_adapter;
	}

/**
 * 
 * @param Iterator $adapter
 * @return Object
 */
	public function __set_c_adapter_method($adapter)
	{
		if (in_array($adapter, array_values($this->__c_arr_adapter)))
		{
			$this->__c_cache_adapter_method = $adapter;
		}
		else 
		{
			return false;
		}
		
		return $this;
	}
	
	public function __get_c_adapter_method()
	{
		return $this->__c_cache_adapter_method;
	}
	
	public function __set_c_adapter($adapter)
	{
		if (!isset($this->__c_cache_adapter_method) || !is_scalar($this->__c_cache_adapter_method) || 
			 empty($this->__c_cache_adapter_method) || !in_array ($this->__c_cache_adapter_method, array_values($this->__c_arr_adapter)))
		{
			return false;
		}
		
		$this->{$this->__c_cache_adapter_method} = $adapter;
		return $this;
	}
	
	public function __get_c_adapter()
	{
		if (!isset($this->__c_cache_adapter_method) || !is_scalar($this->__c_cache_adapter_method) ||
			 empty($this->__c_cache_adapter_method) || !in_array ($this->__c_cache_adapter_method, array_values($this->__c_arr_adapter)))
		{
			return false;
		}
		
		switch ($this->__c_cache_adapter_method) 
		{
			case $this->__c_arr_adapter['F']:
				return $this->__c_f;
				break;
				
			case $this->__c_arr_adapter['R']:
				return $this->__c_r;
				break;
				
			default; return false; break;
		}
	}
	
	public function __set_iterator($iterator)
	{
		$this->iterator = $iterator; return $this;
	}
	
	public function __get_iterator()
	{
		return $this->iterator;
	}
	
	public function __set_arr($arr)
	{
		$this->arr = $arr; return $this;
	}
	
	public function __get_arr()
	{
		return $this->arr;
	}
	
	public function __set_str($str)
	{
		$this->str = $str; return $this;
	}
	
	public function __get_str()
	{
		return $this->str;
	}
	
	public function __c_is_str()
	{
		if (empty($this->__get_str()))
		{
			return false;
		}
		
		return true;
	}
	
	public function __c_write_log($doc = 'default', $filename = 'default')
	{
		if (!$this->__c_is_str() || empty($doc) || empty($filename))
		{
			return false;
		}
		
		if (!file_exists($this->__c_path))
		{
			@mkdir($this->__c_path);
		}
		
		$path = '/'.date('Y-m-d');
		
		if (!file_exists($this->__c_path.$path))
		{
			@mkdir($this->__c_path.$path);
		}
		
		$aSperateDocument = explode('|', $doc);
		
		foreach ($aSperateDocument as $vDoc)
		{
			$path .= '/'.$vDoc;
			
			if (!file_exists($this->__c_path.$path))
			{
				@mkdir($this->__c_path.$path);
			}
		}
		
		$writer = new Stream($this->__c_path.$path.'/'.$filename.'.log');
		$logger = new Logger();
		$logger->addWriter($writer);
		$logger->info($this->__get_str());
	}

	public function __c_source_adapter($iterator)
	{
		if (!$this->__set_iterator($iterator)->__c_is_iterator())
		{
			return false;
		}
		
		if (!isset($this->__c_cache_adapter_method) || !is_scalar($this->__c_cache_adapter_method) ||
			 empty($this->__c_cache_adapter_method) || !in_array ($this->__c_cache_adapter_method, array_values($this->__c_arr_adapter)))
		{
			return false;
		}
		
		switch ($this->__c_cache_adapter_method)
		{
			case $this->__c_arr_adapter['F']:
				
			/**
			 * @access intialize the cache configure
			 */
				if ($this->__c_f != false) 
				{
					if ($iterator->offsetExists('namespace')) 
					{
						$this->__c_f->getOptions()->setNamespace($iterator->offsetGet('namespace'));
					}
				}
				
				return $this->__c_f;
				break;
				
			case $this->__c_arr_adapter['R']:
				
				if ($this->__c_r != false)
				{
					
				}
				
				return $this->__c_r;
				break;
				
			default: return false; break;
		}
	}

	public function __c_is_iterator()
	{
		return $this->iterator instanceof Traversable ? true : false;
	}
	
	public function __c_iterator_to_array()
	{
		return $this->__c_is_iterator() ? ArrayUtils::iteratorToArray($this->iterator) : $this->iterator;
	}
	
	public function __c_array_to_iterator()
	{
		return is_array($this->arr) ? new ArrayIterator($this->arr) : $this->arr;
	}
	
	public function __c_source_o_to_m()
	{
		if (!$this->iterator->offsetExists('sourceData') || !$this->iterator->offsetExists('source') ||  
			!$this->iterator->offsetExists('alias') || !is_array($this->iterator->offsetGet('sourceData')))
		{
			return false;
		}
		
		$data = array();
		
		foreach ($this->iterator->offsetGet('sourceData') as $vData)
		{
			if (is_array($vData))
			{
				$aliasToArray = array();
		
				foreach ($vData as $field => $value)
				{
					$seperate = explode('.', $field);
						
					switch ($seperate[0])
					{
						case $this->iterator->offsetGet('source'):
								
							if (isset($value))
							{
								$data[$vData[$this->iterator->offsetGet('source').'.'.'id']][$seperate[1]] = $value;
							}
								
							break;
								
						case $this->iterator->offsetGet('alias'):
								
							if (isset($value))
							{
								$aliasToArray[$seperate[1]] = $value;
							}
								
							break;
								
						default:break;
					}
				}
		
				if (!empty($aliasToArray))
				{
					$data[$vData[$this->iterator->offsetGet('source').'.'.'id']][$this->iterator->offsetGet('alias')][] = $aliasToArray;
				}
			}
		}
		
		sort($data);return $data;
	}
	
	public function __c_callback_handle($callback = null, array $metadata = array())
	{
		$oCallbackHandle = new CallbackHandler($callback);
		return $oCallbackHandle->call($metadata);
	}
}