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

use Administrator\Model\Menu;
use Administrator\Form\MenuForm;
use Administrator\Model\MenuInfo;
use Administrator\Form\MenuInfoForm;
use Administrator\Model\MenuModelInterface;
use Administrator\Service\CommonServiceInterface;
use Administrator\Service\CoreServiceInterface;

class MenuController extends AbstractActionController
{
	protected $cCore;
	protected $cMenu;
	protected $cFMenu;
	protected $cMMenu;
	protected $cCommon;
	protected $cMenuInfo;
	protected $cFMenuInfo;
	
	public function __construct(
		Menu $oMenu, MenuForm $oFMenu, MenuInfo $oMenuInfo, MenuInfoForm $oFMenuInfo,
		MenuModelInterface $oMMenu, CommonServiceInterface $oCommon, CoreServiceInterface $oCore)
	{
		$this->cCore = $oCore;
		$this->cMenu = $oMenu;
		$this->cFMenu = $oFMenu;
		$this->cMMenu = $oMMenu;
		$this->cCommon = $oCommon;
		$this->cMenuInfo = $oMenuInfo;
		$this->cFMenuInfo = $oFMenuInfo;
	}
	
	public function listAction()
	{
		$vStaticParameters = $this->cCore->ArrayToIterator(
		array(
			'vStaticTitle' => array('p'=>'MENU','c'=>'LIST_FIELDS_TITLE')
		));
		$administratorMenus = $this->cMMenu->getData();
		return compact('administratorMenus','vStaticParameters');
	} 
	
	public function infoAction()
	{
		$status = FALSE;
		
		if (!$this->getRequest()->isPost())
		{
			echo json_encode(compact('status'));
			exit();
		}
		
		$id = (int) $this->getRequest()->getPost('id');
		
		if ($id <= 0)
		{
			echo json_encode(compact('status'));
			exit();
		}
		else
		{
			$status = TRUE;
			$data = $this->cMMenu->getMenuOptionsByMid($id);
			echo json_encode(compact('status','data'));
			exit();
		}	
	}
	
	public function dataAction()
	{
		$page = 0;
		$rows = 0;
	
		if ($this->getRequest()->isPost())
		{
			$page = (int) $this->getRequest()->getPost('page');
			$rows = (int) $this->getRequest()->getPost('rows');
		}
			
		echo $this->cMMenu->getPagination($page, $rows);
		exit();
	}
	
	public function ajaxMenuSubmitAction()
	{
		$vdrSourceData = $this->cCommon->vdrSourceData(
						 $this->getRequest(), $this->cMenu, $this->cFMenu);
		if (!is_object($vdrSourceData))
		{
			echo json_encode($vdrSourceData);
			exit();
		}
		echo json_encode($this->cMMenu->insData($vdrSourceData));
		exit();
	}
	
	public function ajaxMenuInfoSubmitAction() 
	{
		$vdrSourceData = $this->cCommon->vdrSourceData(
						 $this->getRequest(), $this->cMenuInfo, $this->cFMenuInfo);
		if (!is_object($vdrSourceData))
		{
			echo json_encode($vdrSourceData);
			exit();
		}
		echo json_encode($this->cMMenu->insInfoData($vdrSourceData));
		exit();
	}
}