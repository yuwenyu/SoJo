<?php
/**
 * Self::Framework by Zend
 *
 * @author Kyle
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Form;

use Zend\Form\Form;

class MenuInfoForm extends Form
{
	public function __construct()
	{
		parent::__construct('menu_options');
		
		$this->add(array('name'=>'menu_id','type'=>'Hidden'));
		$this->add(array('name'=>'info_h','type'=>'Text'));
		$this->add(array('name'=>'info_a','type'=>'Text'));
		$this->add(array('name'=>'info_c','type'=>'Text'));
	}
}