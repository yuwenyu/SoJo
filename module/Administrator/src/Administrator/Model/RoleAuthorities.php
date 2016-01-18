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
use Zend\Validator\Explode;

class RoleAuthorities implements InputFilterAwareInterface
{	
	public $roleId;
	public $roleAuthoritiesId;
	public $fields = array('roleId','roleAuthoritiesId');
	
	protected $inputFilter;
	
	public function formatInsData(RoleAuthorities $oRoleAuthorities)
	{
		$data = array();
		
		if (isset($oRoleAuthorities->fields) && !empty($oRoleAuthorities->fields) && is_array($oRoleAuthorities->fields))
		{
			foreach ($oRoleAuthorities->fields as $field)
			{
				if (isset($oRoleAuthorities->{$field}) && !empty($oRoleAuthorities->{$field}))
				{
					$data[$field] = $oRoleAuthorities->{$field};
				}
			}
		}
		
		return $data;
	}
	
	public function formatParameters($data = array())
	{
		$this->roleId			 = (isset($data['role_id']) && !empty($data['role_id'])) ? $data['role_id'] : null;
		$this->roleAuthoritiesId =  null;
		if (isset($data['role_authorities_id']) && !empty($data['role_authorities_id']))
		{
			$aRoleAuthoritiesId = explode(',', $data['role_authorities_id']);
			
			if (!empty($aRoleAuthoritiesId) && is_array($aRoleAuthoritiesId))
			{
				$this->roleAuthoritiesId = array_filter($aRoleAuthoritiesId);
			}
		}
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
				'name' => 'role_id',
				'filters' => array(
						array('name' => 'StringTrim')),
				'validators' => array(
						array('name' => 'IsInt')),
				'required' => true
			));
			
			$inputFilter->add(array(
				'name' => 'role_authorities_id',
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