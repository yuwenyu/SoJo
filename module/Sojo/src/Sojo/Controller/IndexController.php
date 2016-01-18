<?php

namespace Sojo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway; 

class IndexController extends AbstractActionController
{
	private $cAdapter;
	
	public function __construct(TableGateway $oTest)
	{
		$this->cTest = $oTest;
	}

	public function indexAction()
	{
		print_r($this->cTest->select()->toArray());
		die('test success');
	}
}