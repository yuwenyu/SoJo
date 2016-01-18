<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Service;

use Administrator\Service\CoreServiceInterface;
use Administrator\Service\CommonServiceInterface;

class CommonService implements CommonServiceInterface
{
	private $sCore;
	
	public function __construct(CoreServiceInterface $oCore) 
	{
		$this->sCore = $oCore;	
	}
	
	public function isSourceExistByFields(
		$oMSource, array $fields = array(), $callback = null, array $metaData = array())
	{
		if (!is_object($oMSource))
		{
			return false;
		}
		
		if (empty($fields) || !is_array($fields))
		{
			return false;
		}
		
		foreach ($fields as $kField => $vField)
		{
			if (empty($vField))
			{
				return false;
			}
		}
		
		$bCallbackHandler = true;
		
		if (!is_null($callback) && is_callable($callback))
		{
			$bCallbackHandler = $this->sCore->CallbackHandler($callback, $metaData);
		}
		
		if ($bCallbackHandler == false)
		{
			return $bCallbackHandler;
		}
		
		$iIsSourceExistByFields = $oMSource->getModuleTb()->select($fields)->count();
		return $iIsSourceExistByFields > 0 ? true : false;
	}
	
	public function insSourceData($oSource, $oMSource, $callback = null, array $metaData = array())
	{
		$id = (int) $oSource->id;
		$aSource = $oSource->formatInsData($oSource);
		
		$iCallbackHandler = 1000;
		
		if (!is_null($callback) && is_callable($callback))
		{
			$iCallbackHandler = $this->sCore->CallbackHandler($callback, $metaData);
		}
		
		if ($iCallbackHandler != 200)
		{
			return $this->getErrorMsg($iCallbackHandler);
		}
		
		if ($id == 0)
		{	
			if (!$oMSource->getModuleTb()->insert($aSource))
			{
				return $this->getErrorMsg(401);
			}
		}
		else
		{
			if (!$oMSource->getModuleTb()->update($aSource, compact('id')))
			{
				return $this->getErrorMsg(403);
			}
		}
		
		return $this->getSuccessMsg();
	}
	
	public function getSourceData($sStaticIdentity = '', 
								  $callback = null, array $metaData = array(), $cache = true)
	{
		if (empty($sStaticIdentity))
		{
			return null;
		}
		
		$aStaticSource = array();
		
		if ($cache === true && $this->sCore->getCacheAdapter()->hasItem($sStaticIdentity))
		{
			$aStaticSource = unserialize($this->sCore->getCacheItemByKey($sStaticIdentity));
		}
		
		if (!empty($aStaticSource) && is_array($aStaticSource))
		{
			return $aStaticSource;
		}
		
		if (!is_null($callback) && is_callable($callback))
		{
			$aStaticSource = $this->sCore->CallbackHandler($callback, $metaData);
		}
		
		if (empty($aStaticSource) && !is_array($aStaticSource))
		{
			return array();
		}
		
		$this->sCore->getCacheAdapter()->setItem($sStaticIdentity, serialize($aStaticSource));
		return $aStaticSource;
	}
	
	public function vdrSourceData($request, $oSource, $oFSource, $method = 'POST', $validator = '')
	{
		if (!is_object($request) || !is_object($oSource) || !is_object($oFSource))
		{
			return $this->getErrorMsg(1001);
		}
		
		$requestData = array();
		
		switch ($method)
		{
			case 'GET':
		
				if (!$request->isGet())
				{
					return $this->getErrorMsg(1003);
				}
		
				$oRequestData = $request->getQuery();
				$aRequestData = $oRequestData->toArray();
				break;
		
			case 'POST':
		
				if (!$request->isPost())
				{
					return $this->getErrorMsg(1001);
				}
		
				$oRequestData = $request->getPost();
				$aRequestData = $oRequestData->toArray();
				break;
		
			default:
		
				return $this->getErrorMsg(1000);
				break;
		}
		
		switch ($validator)
		{
			case 'indeterminate':
				// do something ...
				break;
		
			default:
		
				if (!isset($aRequestData))
				{
					return $this->getErrorMsg(1004);
				}
		
				if (is_scalar($aRequestData))
				{
					return $this->getErrorMsg(1006);
				}
		
				if (!is_array($aRequestData))
				{
					return $this->getErrorMsg(1005);
				}
		
				break;
		}
		
		try
		{
			$oFSource->setInputFilter($oSource->getInputFilter())->setData($oRequestData);
			if (!$oFSource->isValid())
			{
				return $this->getErrorMsg(402);
			}
			$oSource->formatParameters($oFSource->getData());
			return $oSource;
		}
		catch (\Exception $e)
		{
			return $this->getErrorMsg(1000, array(), $e->getMessage());
		}
	}
	
	public function getErrorMsg($code = '', $data = array(), $msg = FALSE) 
	{
		return $this->sCore->getSourceMsg($this->sCore->ArrayToIterator(compact('code','data','msg')));
	}
	
	public function getSuccessMsg($data = array(), $msg = FALSE)
	{
		return $this->getErrorMsg(200, $data, $msg);
	}
}