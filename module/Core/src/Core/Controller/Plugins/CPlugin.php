<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Core\Controller\Plugins;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Core\Service\C;

class CPlugin extends AbstractPlugin
{	
	private $c;
	
	public function __construct(C $c)
	{
		$this->c = $c;
	}
	
	public function setLog($doc = '', $filename = '', $msg = '')
	{
		$this->c->__set_str($msg)->__c_write_log($doc, $filename, $msg);
	}
}