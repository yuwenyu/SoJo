<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Model;

interface AuthorityModelInterface extends ModelInterface
{
	/**
	 * Data insert | Database authorities
	 * 
	 * @param Acl $oAcl
	 */
	public function insData(Acl $oAcl);
	
	/**
	 * Data Attain | Database authorities
	 * 
	 * @param unknown $cache
	 */
	public function getData($cache);
	
	/**
	 * Data Exist or not by Module, Controller & Action
	 * 
	 * @param array $aAuthrity
	 */
	public function isAuthorityExistByMCA(array $aAuthrity);
	
	/**
	 * Data Exist or not by Identity
	 * 
	 * @param int $id
	 */
	public function isAuthorityExistById($id);
}