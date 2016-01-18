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

use Administrator\Model\RoleModelInterface;
use Administrator\Service\CoreServiceInterface;
use Administrator\Service\CommonServiceInterface;
use Zend\Db\Sql\Select;

class RoleModel implements RoleModelInterface
{
	protected $mCore;
	protected $mCommon;
	protected $mRoleTb;
	protected $mRoleAuthorities;
	
	public function __construct(TableGateway $oRoleTb, TableGateway $oRoleAuthorities, 
								CoreServiceInterface $oCore, CommonServiceInterface $oCommon)
	{
		$this->mCore = $oCore;
		$this->mRoleAuthorities = $oRoleAuthorities;
		$this->mCommon = $oCommon;
		$this->mRoleTb = $oRoleTb;
	}
	
	public function getPagination($iPageNumber = 0, $iCountPerPage = 0, $aOptions = array())
	{
		$paginatorAdapter = new DbSelect($this->mRoleTb->getSql()->select()->where($aOptions),
										 $this->mRoleTb->getAdapter(),
										 $this->mRoleTb->getResultSetPrototype());
		
		$paginator = new Paginator($paginatorAdapter);
		$paginator->setCurrentPageNumber($iPageNumber)->setItemCountPerPage($iCountPerPage);
		
		$iTotal = $paginator->getTotalItemCount();
		$aRows = $this->mCore->IteratorToArray($paginator->getCurrentItems());
		
		return json_encode(array('rows'=>$aRows,'total'=>$iTotal));
	}
	
	public function insData(Role $oRole)
	{
		return $this->mCommon->insSourceData(
			$oRole, $this,
			function ($e) use ($oRole)
			{
				$id = (int) $oRole->id;
				$aRole = $oRole->formatInsData($oRole);
	
				if ($id == 0)
				{
					if ($e->isRoleExistByName($aRole['name']))
					{
						return 405;
					}
				}
				else
				{
					if (!$e->isRoleExistById($id))
					{
						return 402;
					}
				}
				return 200;
			}, 
			array('e'=>$this)
		);
	}
	
	public function insRoleAuthorities(RoleAuthorities $oRoleAuthorities)
	{
		$iRoleId = (int) $oRoleAuthorities->roleId;
		$aRoleAuthorties = $oRoleAuthorities->formatInsData($oRoleAuthorities);
		
		$this->mCore->getDbAdapter()->getDriver()->getConnection()->beginTransaction();
		try 
		{
			if (empty($aRoleAuthorties['roleAuthoritiesId']) || !is_array($aRoleAuthorties['roleAuthoritiesId']))
			{
				$this->mCommon->getErrorMsg(1000);
			}
			else 
			{
				$this->mRoleAuthorities->delete(array('role_id' => $aRoleAuthorties['roleId']));
				$aInsRoleAuthorties[] = array();
				foreach ($aRoleAuthorties['roleAuthoritiesId'] as $vRoleAuthorityId)
				{
					$this->mRoleAuthorities->insert(array('role_id' => $aRoleAuthorties['roleId'],
												   		  'authority_id' => $vRoleAuthorityId));
				}
				$this->getRoleAuthorities($aRoleAuthorties['roleId'], false);
			}
			$this->mCore->getDbAdapter()->getDriver()->getConnection()->commit();
			return $this->mCommon->getSuccessMsg();
		}
		catch (\Exception $e)
		{
			$this->mCore->getDbAdapter()->getDriver()->getConnection()->rollback();
			return $this->mCommon->getErrorMsg(1000);
		}
	}
	
	public function getRoleAuthorities($iRole = 0, $cache = true)
	{
		if ((int) $iRole < 1)
		{
			return array();
		}
		
		return $this->mCommon->getSourceData('StaticRoleAuthorities_'.$iRole, 
			function ($eRoleAuthorities) use  
					 ($iRole) {
				$aRoleAuthorities = $eRoleAuthorities->select(function (\Zend\Db\Sql\Select $s) {
					$s->columns(array('id','role_id','authority_id'));
				})->toArray();
				
				if (empty($aRoleAuthorities))
				{
					return array();
				}
				
				$aRefactorRoleAuthorities = array();
				
				foreach ($aRoleAuthorities as $vRoleAuthority)
				{
					$aRefactorRoleAuthorities[$vRoleAuthority['role_id']][] = $vRoleAuthority['authority_id'];
				}
				
				if (!isset($aRefactorRoleAuthorities[$iRole]))
				{
					return array();
				}
				
				return $aRefactorRoleAuthorities[$iRole];
			}, 
			array('eRoleAuthorities'=>$this->mRoleAuthorities), $cache
		);
	}
	
	public function isRoleExistById($id = '')
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
	
	public function isRoleExistByName($name = '')
	{
		return $this->mCommon->isSourceExistByFields($this, compact('name'));
	}
	
	public function getModuleTb()
	{
		return $this->mRoleTb;
	}
}