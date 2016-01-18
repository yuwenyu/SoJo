<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\Model;

interface MenuModelInterface extends ModelInterface
{
/**
 * Data insert | Database menus
 * 
 * @param Menu $m
 */
	public function insData(Menu $m);
	
/**
 * Data insert | Database menu_options
 * 
 * @param MenuInfo $mInfo
 * @return array
 */
	public function insInfoData(MenuInfo $mInfo);
	
/**
 * 
 * @param boolean $cache
 */
	public function getData($cache);
	
/**
 * 
 * @param int $id
 */
	public function getById($id);
	
/**
 * 
 * @param int $mId
 * @param boolean $cache
 */
	public function getMenuOptionsByMid($mId, $cache);
}