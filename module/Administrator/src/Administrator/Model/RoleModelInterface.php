<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Model;

interface RoleModelInterface extends ModelInterface
{
	/**
	 * Data insert | Database roles
	 * 
	 * @param Role $oRole
	 */
	public function insData(Role $oRole);
	
	/**
	 * Data get | Database role_authority
	 */
	public function getRoleAuthorities($iRole);
	
	/**
	 * Data insert & update | Database role_authority
	 * 
	 * @param RoleAuthorities $oRoleAuthorities
	 */
	public function insRoleAuthorities(RoleAuthorities $oRoleAuthorities);
	
	/**
	 * Data Exist or not by Name
	 *
	 * @param string $role
	 */
	public function isRoleExistByName($name);
	
	/**
	 * Data Exist or not by Identity
	 *
	 * @param int $id
	*/
	public function isRoleExistById($id);
}