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

class Role implements InputFilterAwareInterface
{	
	public $id;
	public $name;
	public $remark;
	public $fields = array('name','remark');
	
	protected $inputFilter;
	
	public function formatInsData(Role $oRole)
	{
		$data = array();
		
		if (isset($oRole->fields) && !empty($oRole->fields) && is_array($oRole->fields))
		{
			foreach ($oRole->fields as $field)
			{
				if (isset($oRole->{$field}) && !empty($oRole->{$field}))
				{
					$data[$field] = $oRole->{$field};
				}
			}
		}
		
		return $data;
	}
	
	public function formatParameters($data = array())
	{
		$this->id		= (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;
		$this->name 	= (isset($data['name']) && !empty($data['name'])) ? $data['name'] : null;
		$this->remark 	= (isset($data['remark']) && !empty($data['remark'])) ? $data['remark'] : null;
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
				'name' => 'name',
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
				'name' => 'remark',
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim')),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
							'encoding' => 'UTF-8',
							'min' => 1, 
							'max' => 50
						)
					)),
				'required' => false
			));
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
}