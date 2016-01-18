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

use Administrator\Form\AclForm;
use Administrator\Model\Acl;
use Administrator\Model\AuthorityModelInterface;
use Administrator\Service\CoreServiceInterface;
use Administrator\Service\CommonServiceInterface;

class AclController extends AbstractActionController
{
	private $cAcl;
	private $cFAcl;
	private $cCore;
	private $cCommon;
	private $cMAuthority;
	
	public function __construct(Acl $mAcl, AclForm $mFAcl, 
		CoreServiceInterface $oCore, CommonServiceInterface $sCommon, AuthorityModelInterface $mAuthority)
	{
		$this->cAcl = $mAcl;
		$this->cFAcl = $mFAcl;
		$this->cCore = $oCore;
		$this->cCommon = $sCommon;
		$this->cMAuthority = $mAuthority;
	}
	
	public function listAction() 
	{
		$vStaticParameters = $this->cCore->ArrayToIterator(
		array(
			'vStaticTitle' => array('p'=>'ACL','c'=>'LIST_FIELDS_TITLE')
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
			
			if (!empty($this->getRequest()->getPost('search_type')) && 
				!empty($this->getRequest()->getPost('search_value'))) 
			{
				$aOptions[$this->getRequest()->getPost('search_type')] = $this->getRequest()->getPost('search_value'); 
			}
		}
		
		echo $this->cMAuthority->getPagination($iPage, $iRows, $aOptions);
		exit();
	}
	
	public function ajaxAclSubmitAction()
	{
		$vdrSourceData = $this->cCommon->vdrSourceData(
				$this->getRequest(), $this->cAcl, $this->cFAcl);
		if (!is_object($vdrSourceData))
		{
			echo json_encode($vdrSourceData);
			exit();
		}
		
		echo json_encode($this->cMAuthority->insData($vdrSourceData));
		exit();
	}
}