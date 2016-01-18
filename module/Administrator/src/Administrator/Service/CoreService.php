<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Service;

use Core\Service\C;
use Administrator\Service\CoreServiceInterface;

class CoreService implements CoreServiceInterface
{
/**
 * 
 * @var C
 */
	private $c;
	
/**
 * 
 * @var Config
 */
	private $aConfigure;
	
	public function __construct($aConfigure, C $c)
	{
		$this->c = $c;
		$this->aConfigure = $aConfigure;
	}
	
	public function getCurrentConfiguration()
	{
		return $this->aConfigure;
	}
	
	public function getToRouteConfiguration($route = array())
	{
		$serviceConfigManager = $this->getCurrentConfiguration();
		$serviceAdministratorConfigManager = $serviceConfigManager['router']['routes'][$_SERVER['HTTP_HOST']];
		
		if (!isset($serviceAdministratorConfigManager['options']['constraints']) || 
			 empty($serviceAdministratorConfigManager['options']['constraints']))
		{
			return null;
		}	
		
		$router_options = array();
		
		foreach ($serviceAdministratorConfigManager['options']['constraints'] as $dom => $val)
		{
			if (in_array($dom, array('4th','3rd','2nd','1st'))) 
			{
				$router_options[$dom] = $val;
			}	
		}
		
		if (isset($route['controller']) && isset($route['action']))
		{
			$router_options['controller'] = $route['controller'];
			$router_options['action'] = $route['action'];		
		}
		
		return $router_options;
	}
	
	public function getDbAdapter()
	{
		return $this->c->__get_db_adapter();
	}
	
	public function getStaticParameters($iterator = TRUE)
	{
		$aStaticParameters = unserialize($this->getCacheItemByKey('StaticParameters'));
		return $iterator ? $this->ArrayToIterator($aStaticParameters) : $this->IteratorToArray($aStaticParameters);
	}
	
	public function setAdapterMethod($adapter = 'F')
	{
		$this->c->__set_c_adapter_method($this->c->__c_arr_adapter[$adapter]);
		return $this;
	}
	
	public function getAdapterMethod()
	{
		return $this->c->__get_c_adapter_method();
	}
	
	public function getCacheAdapter()
	{	
		if ($this->c->__get_c_adapter_method() == false)
		{
			$this->c->__set_c_adapter_method($this->c->__c_arr_adapter['F']);	
		}
		
		$oCacheAdapter = $this->c->__c_source_adapter(
			$this->ArrayToIterator(array('namespace'=>'Administrator')));
		if ($oCacheAdapter == false)
		{
			$this->c->__set_str("Service Core Line 102 : Cache Adapter {$this->c->__get_c_adapter_method()} Empty or Error!")
				 ->__c_write_log('Administrator|CoreService','getCacheAdapter');
			exit('Fatal Error : Administrator|CoreService');
		}
		
		return $oCacheAdapter;
	}
	
	public function getCacheItemByKey($key)
	{
		$aCacheItem = $this->getCacheAdapter()->getItem($key);
		
		if (empty($aCacheItem))
		{
			return false;
		}
		
		switch ($this->c->__get_c_adapter_method())
		{
			case $this->c->__c_arr_adapter['F']:
				
				if ($aCacheItem instanceof Traversable)
				{
					return $this->IteratorToArray($aCacheItem);
				}
				
				return $aCacheItem; break;
			
			case $this->c->__c_arr_adapter['R']:
				// do something ...
				break;
				
			default: return false; break;
		}
	}
	
	public function HasManyToArray($sourceData = array(), $source = '', $alias = '')
	{
		return $this->c->__set_iterator(
			   $this->c->__set_arr(compact('sourceData','source','alias'))->__c_array_to_iterator())
					   ->__c_source_o_to_m();
	}
	
	public function ArrayToIterator($arr)
	{
		return $this->c->__set_arr($arr)->__c_array_to_iterator();
	}
	
	public function IteratorToArray($iterator)
	{
		return $this->c->__set_iterator($iterator)->__c_iterator_to_array();
	}
	
	public function isIterator($iterator)
	{
		return $this->c->__set_iterator($iterator)->__c_is_iterator();
	}
	
	public function CallbackHandler($callback = null, array $metaData = array())
	{
		return $this->c->__c_callback_handle($callback, $metaData);
	}
	
	public function getSourceMsg($iterator) 
	{
		if (!$this->c->__set_iterator($iterator)->__c_is_iterator())
		{
			return false;
		}
		
		$oCallbackHandle = $this->CallbackHandler(
			function () use 
					 ($iterator) 
			{
				$callback = array();
				
				if (!$iterator->offsetExists('code') || empty($iterator->offsetGet('code')))
				{
					$iterator->offsetSet('code', 400);
				}
				$callback['code'] = $iterator->offsetGet('code');
				
				if (!$iterator->offsetExists('status') || !is_bool($iterator->offsetGet('status')))
				{
					$iterator->offsetSet('status', $iterator->offsetGet('code') == 200 ? true : false);
				}
				$callback['status'] = $iterator->offsetGet('status');
				
				if ($iterator->offsetExists('msg') && $iterator->offsetGet('msg') != false)
				{
					$callback['msg'] = $iterator->offsetGet('msg');
				}
				
				if ($iterator->offsetExists('data') && !empty($iterator->offsetGet('data')) && is_array(
					$iterator->offsetGet('data')))
				{
					$callback['data'] = $iterator->offsetGet('data');
				}
				
				return $callback;
			}
		);
		
		return $oCallbackHandle;
	}
}