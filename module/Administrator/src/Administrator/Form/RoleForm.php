<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Form;

use Zend\Form\Form;

class RoleForm extends Form
{
	public function __construct()
	{
		parent::__construct('role');
		
		$this->add(array('name'=>'id','type'=>'Hidden'));
		$this->add(array('name'=>'name','type'=>'Text'));
		$this->add(array('name'=>'remark','type'=>'Text'));
	}
}