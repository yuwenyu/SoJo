<?php
/**
 * Self::Framework by Zend
 *
 * @author Kyle
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class MenuInfo implements InputFilterAwareInterface
{
	public $fields = array('href'=>'info_h', 'action'=>'info_a', 'controller'=>'info_c');
	
	public $info_h;
	public $info_a;
	public $info_c;
	public $menu_id;
	
	protected $inputFilter;
	
	public function formatParameters($data = array())
	{
		$this->info_h = isset($data['info_h']) && !empty($data['info_h']) ? $data['info_h'] : null;
		$this->info_a = isset($data['info_a']) && !empty($data['info_a']) ? $data['info_a'] : null;
		$this->info_c = isset($data['info_c']) && !empty($data['info_c']) ? $data['info_c'] : null;
		$this->menu_id = isset($data['menu_id']) && !empty($data['menu_id']) ? $data['menu_id'] : null;
	}
	
	public function setInputFilter(InputFilterInterface $inputFilterInterface)
	{
		throw new \Exception('Not Used');
	}
	
	public function getInputFilter()
	{
		if (!$this->inputFilter)
		{
			$inputFilter = new InputFilter();
				
			$inputFilter->add(array(
				'name' => 'menu_id',
				'filters' => array(
					array('name' => 'StringTrim')),
				'validators' => array(
					array('name' => 'NotEmpty'),
					array('name' => 'IsInt')),
				'required' => true
			));
			
			$inputFilter->add(array(
				'name' => 'info_h',
				'filters' => array(
					array('name' => 'StringTrim')),
				'validators' => array(
					array('name' => 'NotEmpty'),
					array('name' => 'IsInt')),
				'required' => true
			));
			
			$inputFilter->add(array(
				'name' => 'info_a',
				'filters' => array(
					array('name' => 'StringTrim')),
				'validators' => array(
					array('name' => 'NotEmpty')),
				'required' => true
			));
			
			$inputFilter->add(array(
				'name' => 'info_c',
				'filters' => array(
					array('name' => 'StringTrim')),
				'validators' => array(
					array('name' => 'NotEmpty')),
				'required' => true
			));
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
}