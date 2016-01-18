<?php 
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Administrator\Form\RoleForm;
use Administrator\Form\RoleAuthoritiesForm;
use Administrator\Model\Role;
use Administrator\Model\RoleAuthorities;
use Administrator\Model\RoleModelInterface;
use Administrator\Model\AuthorityModelInterface;
use Administrator\Service\CoreServiceInterface;
use Administrator\Service\CommonServiceInterface;

class RoleController extends AbstractActionController
{
	private $cRole;
	private $cRoleAuthorities;
	private $cFRole;
	private $cFRoleAuthorities;
	private $cMRole;
	private $cCore;
	private $cCommon;
	private $cMAuthority;
	
	public function __construct(
		Role $oRole, RoleForm $oFRole, 
		RoleAuthorities $oRoleAuthorities, RoleAuthoritiesForm $oFRoleAuthorities,
		CoreServiceInterface $oCore, CommonServiceInterface $oCommon, 
		RoleModelInterface $oMRole, AuthorityModelInterface $oMAuthority)
	{
		$this->cRole = $oRole;
		$this->cRoleAuthorities = $oRoleAuthorities;
		$this->cFRole = $oFRole;
		$this->cFRoleAuthorities = $oFRoleAuthorities;
		$this->cMRole = $oMRole;
		$this->cCore = $oCore;
		$this->cCommon = $oCommon;
		$this->cMAuthority = $oMAuthority;
	}
	
	public function listAction()
	{
		$vStaticParameters = $this->cCore->ArrayToIterator(
		array(
			'vStaticTitle' => array('p'=>'ROLE','c'=>'LIST_FIELDS_TITLE'),
			'vAuthorities' => $this->cMAuthority->getData()
		));
		return compact('vStaticParameters');
	}
	
	public function dataAction()
	{
		$iPage = 0;
		$iRows = 0;
		$aOptions = array();
		
		if ($this->getRequest()->isPost())
		{
			$iPage = (int) $this->getRequest()->getPost('page');
			$iRows = (int) $this->getRequest()->getPost('rows');
		}
		
		echo $this->cMRole->getPagination($iPage, $iRows, $aOptions);
		exit();
	}
	
	public function ajaxRoleAuthoritiesAction()
	{	
		$sRoleAuthorities = '';
		if ((int) $this->getRequest()->getPost('role_id') >= 1 && $this->getRequest()->isPost())
		{
			$sRoleAuthorities = implode(',',$this->cMRole->getRoleAuthorities(
											$this->getRequest()->getPost('role_id')));
		}
		echo json_encode(compact('sRoleAuthorities'));
		exit();
	}
	
	public function ajaxRoleSubmitAction()
	{
		$vdrSourceData = $this->cCommon->vdrSourceData(
						 $this->getRequest(), $this->cRole, $this->cFRole);
		if (!is_object($vdrSourceData))
		{
			echo json_encode($vdrSourceData);
			exit();
		}
		echo json_encode($this->cMRole->insData($vdrSourceData));
		exit();
	}
	
	public function ajaxRoleAuthoritiesSubmitAction()
	{
		$vdrSourceData = $this->cCommon->vdrSourceData(
						 $this->getRequest(), $this->cRoleAuthorities, $this->cFRoleAuthorities);
		if (!is_object($vdrSourceData))
		{
			echo json_encode($vdrSourceData);
			exit();
		}
		echo json_encode($this->cMRole->insRoleAuthorities($vdrSourceData));
		exit();
	}
}