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

use Administrator\Service\CoreServiceInterface;
use Administrator\Model\AdministratorModelInterface;

class AdministratorModel implements AdministratorModelInterface
{
	protected $mCore;
	protected $mAdministratorTb;
	
	public function __construct(TableGateway $oAdministratorTb, CoreServiceInterface $oCore)
	{
		$this->mCore = $oCore;
		$this->mAdministratorTb = $oAdministratorTb;
	}
	
	public function getPagination($iPageNumber = 0, $iCountPerPage = 0)
	{
		$paginatorAdapter = new DbSelect($this->mAdministratorTb->getSql()->select()->columns(array('id','role_id','username','realname','raw_add_time')), 
										 $this->mAdministratorTb->getAdapter(),
										 $this->mAdministratorTb->getResultSetPrototype());
		
		$paginator = new Paginator($paginatorAdapter);
		$paginator->setCurrentPageNumber($iPageNumber)->setItemCountPerPage($iCountPerPage);
		
		$aRows = $this->mCore->IteratorToArray($paginator->getCurrentItems());
		$iTotal = $paginator->getTotalItemCount();
		
		return json_encode(array('rows'=>$aRows,'total'=>$iTotal));
	}
	
	public function getModuleTb()
	{
		return $this->mAdministratorTb;
	}
}