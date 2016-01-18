<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class Acl implements InputFilterAwareInterface
{	
	public $id;
	public $module;
	public $controller;
	public $action;
	public $fields = array('module', 'controller', 'action');
	
	protected $inputFilter;
	
	public function formatInsData(Acl $a)
	{
		$data = array();
		
		if (isset($a->fields) && !empty($a->fields) && is_array($a->fields))
		{
			foreach ($a->fields as $field)
			{
				if (isset($a->{$field}) && !empty($a->{$field}))
				{
					$data[$field] = $a->{$field};
				}
			}
		}
		
		return $data;
	}
	
	public function formatParameters($data = array())
	{
		$this->id			= (isset($data['id']) && !empty($data['id']) && is_numeric($data['id'])) ? $data['id'] : 0;
		$this->module 		= (isset($data['module']) && !empty($data['module'])) ? $data['module'] : null;
		$this->controller	= (isset($data['controller']) && !empty($data['controller'])) 	? $data['controller'] : null;
		$this->action 		= (isset($data['action']) && !empty($data['action'])) ? $data['action'] : null;
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
				'name' => 'id',
				'filters' => array(
						array('name' => 'StringTrim')),
				'validators' => array(
						array('name' => 'IsInt')),
				'required' => false
			));
			
			$inputFilter->add(array(
				'name' => 'module',
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim')),
				'validators' => array(
					array('name' => 'NotEmpty'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1, 
							'max' => 100
						)
					)),
				'required' => true
			));
			
			$inputFilter->add(array(
				'name' => 'controller',
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim')),
				'validators' => array(
					array('name' => 'NotEmpty'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1, 
							'max' => 100
						)
					)),
				'required' => true
			));
			
			$inputFilter->add(array(
				'name' => 'action',
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim')),
				'validators' => array(
					array('name' => 'NotEmpty'),
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1, 
							'max' => 100
						)
					)),
				'required' => true
			));
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
}