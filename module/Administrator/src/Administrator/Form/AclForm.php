<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Form;

use Zend\Form\Form;

class AclForm extends Form
{
	public function __construct()
	{
		parent::__construct('acl');
		
		$this->add(array('name'=>'id','type'=>'Hidden'));
		$this->add(array('name'=>'module','type'=>'Text'));
		$this->add(array('name'=>'controller','type'=>'Text'));
		$this->add(array('name'=>'action','type'=>'Text'));
	}
}