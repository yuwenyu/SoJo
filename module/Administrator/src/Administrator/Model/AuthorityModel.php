<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

use Administrator\Model\AuthorityModelInterface;
use Administrator\Service\CoreServiceInterface;
use Administrator\Service\CommonServiceInterface;

class AuthorityModel implements AuthorityModelInterface
{
	private $mCore;
	private $mCommon;
	private $mAuthorityTb;
	
	public function __construct(
		TableGateway $oAuthorityTb,
		CoreServiceInterface $oCore, CommonServiceInterface $oCommon)
	{
		$this->mCore = $oCore;
		$this->mCommon = $oCommon;
		$this->mAuthorityTb = $oAuthorityTb;
	}
	
	public function getPagination($iPageNumber = 0, $iCountPerPage = 0, $aOptions = array())
	{
		$paginatorAdapter = new DbSelect($this->mAuthorityTb->getSql()->select()->where($aOptions),
										 $this->mAuthorityTb->getAdapter(),
										 $this->mAuthorityTb->getResultSetPrototype());
		
		$paginator = new Paginator($paginatorAdapter);
		$paginator->setCurrentPageNumber($iPageNumber)->setItemCountPerPage($iCountPerPage);
		
		$iTotal = $paginator->getTotalItemCount();
		$aRows = $this->mCore->IteratorToArray($paginator->getCurrentItems());
		
		return json_encode(array('rows'=>$aRows,'total'=>$iTotal));
	}
	
	public function insData(Acl $oAuthority)
	{
		$aResultInsData = $this->mCommon->insSourceData(
			$oAuthority, $this, 
			function ($e) use ($oAuthority)
			{
				if ($e->isAuthorityExistByMCA($oAuthority->formatInsData($oAuthority)))
				{
					return 405;
				}
				
				$id = (int) $oAuthority->id;
				if ($id > 0)
				{
					if (!$e->isAuthorityExistById($id))
					{
						return 402;
					}
				}
				
				return 200;
			}, 
			array('e'=>$this)
		);
		
		if (isset($aResultInsData['code']) && (int) $aResultInsData['code'] == 200)
		{
			$this->getData(FALSE);
		}
		
		return $aResultInsData;
	}
	
	public function getData($cache = true)
	{
		return $this->mCommon->getSourceData('StaticAuthorities', 
			function ($oMSource)
			{
				return $oMSource->select(
					function (\Zend\Db\Sql\Select $s) {
						$s->columns(array('id','module','controller','action'));
				})->toArray();
			},
			array('oMSource'=>$this->getModuleTb()),$cache
		);
	}
	
	public function isAuthorityExistById($id = '')
	{
		return $this->mCommon->isSourceExistByFields(
			$this, compact('id'),
			function ($id)
			{
				if (empty($id) || !is_numeric($id) || $id < 1)
				{
					return false;
				}
				return true;
			},
			compact('id')
		);
	}
	
	public function isAuthorityExistByMCA(array $aAuthority = array())
	{
		return $this->mCommon->isSourceExistByFields($this, $aAuthority);
	}
	
	public function getModuleTb()
	{
		return $this->mAuthorityTb;
	}
}