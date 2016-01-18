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

class Menu implements InputFilterAwareInterface
{
	public $id;
	public $pid;
	public $name;
	public $status;
	public $fields = array('pid', 'name', 'status');
	
	protected $inputFilter;
	
	public function formatInsData(Menu $oMenu)
	{
		$data = array();
	
		if (isset($oMenu->fields) && !empty($oMenu->fields) && is_array($oMenu->fields))
		{
			foreach ($oMenu->fields as $field) 
			{
				if (isset($oMenu->{$field})) 
				{
					if ($oMenu->{$field} == 0)
					{
						$data[$field] = $oMenu->{$field};
					}
					else
					{
						if (!empty($oMenu->{$field}))
						{
							$data[$field] = $oMenu->{$field};
						}
					}
				}
			}
		}
	
		return $data;
	}
	
	public function formatParameters($data = array())
	{
		$this->id		= (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;
		$this->pid 		= (isset($data['pid']) && !empty($data['pid'])) ? $data['pid'] : 0;
		$this->name		= (isset($data['name']) && !empty($data['name'])) ? $data['name'] : null;
		$this->status 	= (isset($data['status']) && !empty($data['status'])) ? $data['status'] : null;
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
				'name' => 'pid',
				'filters' => array(
					array('name' => 'StringTrim')),
				'validators' => array(
					array('name' => 'NotEmpty'),
					array('name' => 'IsInt')),
				'required' => true
			));
			
			$inputFilter->add(array(
				'name' => 'name',
				'filters' => array(
					array('name' => 'StripTags'),
					array('name' => 'StringTrim')),
				'validators' => array(
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
				'name' => 'status',
				'filters' => array(
					array('name' => 'StringTrim')),
				'validators' => array(
					array('name' => 'NotEmpty'),
					array('name' => 'IsInt')),
				'required' => false
			));
			
			$this->inputFilter = $inputFilter;
		}
		
		return $this->inputFilter;
	}
}