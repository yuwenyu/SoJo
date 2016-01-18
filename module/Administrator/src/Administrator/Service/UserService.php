<?php 
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Service;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;

use Administrator\Service\UserServiceInterface;

class UserService implements UserServiceInterface
{
	private $adapter;
	private $adapterParameters;
	
	private $adapterUsername;
	private $adapterPassword;
	
	private $authConnect;
	
	public function __construct(AdapterInterface $adapter)
	{
		$this->adapter = $adapter;
		$this->authConnect = new AuthenticationService();
	}
	
	public function authenticate()
	{
		$authAdapter = new AuthAdapter($this->adapter);
		$authAdapter
			->setTableName			('administrators')
			->setIdentityColumn		('username')
			->setCredentialColumn	('password');
		$authAdapter
			->setIdentity			($this->adapterUsername)
			->setCredential			($this->adapterPassword);
		
		$authService = $this->authConnect->authenticate($authAdapter);
		
		if ($authService->isValid()) 
		{
			$this->authConnect->getStorage()
				->write($authAdapter->getResultRowObject());
			return array('status'=>'success','code'=>'200','msg'=>$authService->getMessages());
		} 
		else 
		{
			return array('status'=>'failure','code'=>'301','msg'=>$authService->getMessages());
			/*
			switch ($authService->getCode()) 
			{
				case Result::FAILURE_CREDENTIAL_INVALID:
					break;
		
				case Result::FAILURE_IDENTITY_NOT_FOUND:
					break;
						
				default:
					break;
			}
			*/
		}
	}
	
	public function hasAuthStorage()
	{
		return $this->authConnect->hasIdentity();
	}
	
	public function getAuthStorage()
	{
		return $this->authConnect->getIdentity();	
	}
	
	public function setQuit()
	{
		$this->authConnect->clearIdentity();
	}
	
	public function setUsername($username = '')
	{
		$this->adapterUsername = $username;
		return $this;
	}
	
	public function setPassword($password = '')
	{
		$this->adapterPassword = $password;
		return $this;
	}
}