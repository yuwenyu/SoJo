<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Form;

use Zend\Form\Form;

class RoleAuthoritiesForm extends Form
{
	public function __construct()
	{
		parent::__construct('role_authority');
		
		$this->add(array('name'=>'role_id','type'=>'Hidden'));
		$this->add(array('name'=>'role_authorities_id','type'=>'Hidden'));
	}
}