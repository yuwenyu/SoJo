<?php 
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Service;

use Zend\Authentication\Adapter\AdapterInterface;

interface UserServiceInterface extends AdapterInterface 
{
	public function setUsername($username);
	public function setPassword($password);	
	public function setQuit();
	
	public function getAuthStorage();
	public function hasAuthStorage();
}