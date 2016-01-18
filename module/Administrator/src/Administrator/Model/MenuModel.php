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

use Administrator\Model\MenuModelInterface;
use Administrator\Service\CoreServiceInterface;
use Administrator\Service\CommonServiceInterface;

class MenuModel implements MenuModelInterface
{
	protected $mCore;
	protected $mCommon;
	protected $mMenuTb;
	protected $mMenuInfoTb;
	
	public function __construct(
		TableGateway $oMenuTb, TableGateway $oMenuInfoTb,
		CoreServiceInterface $oCore, CommonServiceInterface $oCommon)
	{
		$this->mCore = $oCore;
		$this->mCommon = $oCommon;
		$this->mMenuTb = $oMenuTb;
		$this->mMenuInfoTb = $oMenuInfoTb;
	}
	
	public function insData(Menu $oMenu)
	{
		$aResultInsData = $this->mCommon->insSourceData(
			$oMenu, $this,
			function ($e) use ($oMenu)
			{
				$id = (int) $oMenu->id;
				if ($id == 0)
				{
					if (!$e->getById($id))
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
			$this->getMenuOptionsByMid(0, FALSE);
		}
		
		return $aResultInsData;
	}
	
	public function insInfoData(MenuInfo $mInfo)
	{
		$mId = (int) $mInfo->menu_id;
		
		if ($mId < 1)
		{
			return $this->mCommon->getErrorMsg(402);
		}
		
		if (isset($mInfo->fields) && !empty($mInfo->fields) && is_array($mInfo->fields))
		{
			foreach ($mInfo->fields as $field => $vData)
			{
				if (isset($mInfo->{$vData}) && !empty($mInfo->{$vData}))
				{
					$mInfoData = $this->mMenuInfoTb->select(array('menu_id' => $mId, 'k' => $field))->toArray();
					
					if (empty($mInfoData))
					{
						$insInfoData = array('menu_id' => $mId, 'k' => $field, 'v' => $mInfo->{$vData});
						
						if(!$this->mMenuInfoTb->insert($insInfoData))
						{
							return $this->mCommon->getErrorMsg(401);
						}
					}
					else 
					{
						try 
						{
							$this->mMenuInfoTb->update(array('v' => $mInfo->{$vData}),
													   array('menu_id' => $mId, 'k' => $field));
						}
						catch (\Exception $e)
						{
							return $this->mCommon->getErrorMsg(400);
						}
					}
				}
			}
			
		/**
		 * refrash Data Source Cache (File Cache)
		 */
			@$this->getMenuOptionsByMid(0, FALSE);
			@$this->getMenuOptionsByMid($mId, FALSE);
		}

		return $this->mCommon->getSuccessMsg();
	}
	
	public function getPagination($iPageNumber = 0, $iCountPerPage = 0)
	{
		$paginatorAdapter = new DbSelect(
			$this->mMenuTb->getSql()->select(),
			$this->mMenuTb->getAdapter(),
			$this->mMenuTb->getResultSetPrototype());
		
		$paginator = new Paginator($paginatorAdapter);
		$paginator->setCurrentPageNumber($iPageNumber)->setItemCountPerPage($iCountPerPage);
		
		$iTotal = $paginator->getTotalItemCount();
		$aRows = $this->mCore->IteratorToArray($paginator->getCurrentItems());
		return json_encode(array('rows'=>$aRows,'total'=>$iTotal));
	}
	
	public function getById($id = '')
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
	
	public function getMenuOptionsByMid($mId = 0, $cache = true)
	{
		return $this->mCommon->getSourceData('StaticMenuOptions_'.$mId, 
			function ($oMSource) use ($mId)
			{
				$aSourceData = $oMSource->getModuleTb()->select(
					function (\Zend\Db\Sql\Select $s) use ($mId) 
					{
						$s->columns(array('menus.id'=>'id','menus.pid'=>'pid','menus.name'=>'name'));
						$s->join(
							'menu_options', 'menus.id=menu_options.menu_id', 
					  	array('menu_options.menu_id'=>'menu_id','menu_options.k'=>'k','menu_options.v'=>'v'), 
						\Zend\Db\Sql\Select::JOIN_LEFT);
						
						$conditions = array();
						$conditions['menus.status'] = '1';
						if ($mId > 0) {
							$conditions['menus.id'] = $mId;
						}
						$s->where($conditions);
					}
				)->toArray();
				return $oMSource->mCore->HasManyToArray($aSourceData, 'menus', 'menu_options');
			},
			array('oMSource'=>$this),$cache
		);
	}
	
	public function getData($cache = true)
	{
		return $this->mCommon->getSourceData('StaticMenus',
			function ($oMSource)
			{
				return $oMSource->select(
					function (\Zend\Db\Sql\Select $s) {
						$s->columns(array('id','name'));
					})->toArray();
			},
			array('oMSource'=>$this->getModuleTb()),$cache
		);
	}
	
	public function getModuleTb()
	{
		return $this->mMenuTb;
	}
}