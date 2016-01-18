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
use Zend\Crypt\PublicKey\Rsa\PublicKey;

class IndexController extends AbstractActionController
{
	public function __construct()
	{
		
	}
	
	public function indexAction()
	{
		$this->layout('layout/default');
	}
}