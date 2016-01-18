<?php
/**
 * Self::Framework by Zend
 *
 * @author WenYu
 * @copyright Copyright (c) 2015~2***
 */

namespace Administrator\View\Helper;

use Zend\View\Helper\AbstractHelper;

use Administrator\Service\CoreServiceInterface;
use Administrator\Service\UserServiceInterface;
use Administrator\Model\MenuModelInterface;
use Administrator\Model\RoleModelInterface;
use Administrator\Model\AuthorityModelInterface;

class CoreHelper extends AbstractHelper
{
	private $vCore;
	private $vUser;
	private $vMMenu;
	private $vMRole;
	private $vMAuthority;
	
	public function __construct(CoreServiceInterface $oCore, UserServiceInterface $oUser, 
		MenuModelInterface $oMMenu, AuthorityModelInterface $oMAuthority, RoleModelInterface $oMRole)
	{
		$this->vCore = $oCore;
		$this->vUser = $oUser;
		$this->vMMenu = $oMMenu;
		$this->vMRole = $oMRole;
		$this->vMAuthority = $oMAuthority;
	}
	
	public function rUrl($parameters = array(), $options = array(), $reuseMatchedParams = true)
	{
		if (!isset($parameters['router']) || empty($parameters['router'])) 
		{
			$parameters['router'] = array();
		}
		
		return $this->view->url(isset($parameters['name']) && !empty($parameters['name']) ? 
			   $_SERVER['HTTP_HOST'] . '/' . $parameters['name'] : $_SERVER['HTTP_HOST'] . '/' . 'default',
			   $parameters['router'],$options,$reuseMatchedParams);
	}
	
	public function vAuthStorage()
	{
		return $this->vUser->getAuthStorage();	
	}
	
	public function vAuthorityData()
	{
		$aAuthorities = $this->vMAuthority->getData();
		$iI = 0;
		$iJ = 0;
		$aClrAuthorities = array();
		if (!empty($aAuthorities) && is_array($aAuthorities))
		{
			foreach ($aAuthorities as $vAuthority)
			{
				$bIsModuleExist = false;
				if (isset($aClrAuthorities) && !empty($aAuthorities))
				{
					foreach ($aClrAuthorities as $i => $vModuleChildren) 
					{
						if ($vModuleChildren['text'] == $vAuthority['module'])
						{
							$iThisI = $i;
							$bIsModuleExist = true;
						}
					}
				}
				
				if ($bIsModuleExist == false)
				{
					$iThisI = $iI++;
					$aClrAuthorities[$iThisI]['text'] = $vAuthority['module'];
				}
				
				$bIsControllerExist = false;
				if (isset($aClrAuthorities[$iThisI]['children']))
				{
					foreach ($aClrAuthorities[$iThisI]['children'] as $j => $vControllerChildren)
					{
						if ($vControllerChildren['text'] == $vAuthority['controller'])
						{
							$iThisJ = $j;
							$bIsControllerExist = true;
						}
					}
				}
					
				if ($bIsControllerExist === false)
				{
					$iThisJ = $iJ++;
					$aClrAuthorities[$iThisI]['children'][$iThisJ]['text'] = $vAuthority['controller'];
				}
				
				$aClrAuthorities[$iThisI]['children'][$iThisJ]['children'][] = array(
					'id' => $vAuthority['id'],
					'text' => $vAuthority['action']
				);
			}
		}
		return json_encode($aClrAuthorities);
	}
	
	public function srcStaticParameters($method = '', $variable = '')
	{
		if (empty($method) || empty($method))
		{
			return 'NULL';
		}
		
		$iStaticParameters = $this->vCore->getStaticParameters();
		
		if (!$iStaticParameters->offsetExists($method) || !isset($iStaticParameters->offsetGet($method)[$variable]))
		{
			return 'NULL';
		}
		
		return $iStaticParameters->offsetGet($method)[$variable];
	}
	
	public function srcTree()
	{
		return $this->recursionTree($this->getTree($this->vMMenu->getMenuOptionsByMid(),0));
	}
	
	private function getTree($tree = array(), $pid = 0)
	{
		if (empty($tree) || !is_array($tree))
		{
			return FALSE;
		}
		
		$oTree = array();
		
		foreach ($tree as $v)
		{
			if ($v['pid'] == $pid)
			{
				$oTreeChildren = $this->getTree($tree, $v['id']);
				
				$oTreeArrange = array();
				$oTreeArrange['text'] = $v['name'];
				
				if (!empty($oTreeChildren)) 
				{
					$oTreeArrange['children'] = $oTreeChildren;
				}
				
				if (isset($v['menu_options']) && is_array($v['menu_options'])) 
				{
					foreach ($v['menu_options'] as $vOption) 
					{
						if (in_array($vOption['k'], array('href','action','controller')))
						{
							$oTreeArrange[$vOption['k']] = $vOption['v'];	
						}
					}
				}
				
				$oTree[] = $oTreeArrange; unset($oTreeArrange);
			}
		}
		
		return !empty($oTree) && is_array($oTree) ? $oTree : FALSE;
	}
	
	private function recursionTree($srcTree = array())
	{
		if (empty($srcTree))
		{
			return null;
		}
		
		foreach ($srcTree as $k => $tree)
		{	
			if (isset($tree['children'])) 
			{
				if (isset($tree['href']) && $tree['href'] && isset($tree['controller']) && isset($tree['action']))
				{
					$tree['href_text'] = $this->rUrl(array(
							'router'=>array('controller' => $tree['controller'], 'action' => $tree['action'])));
				}
				
				$srcTree[$k] = $tree;
				$srcTree[$k]['children'] = $this->recursionTree($tree['children']);
			}
			else
			{	
				if (isset($tree['href']) && $tree['href'] && isset($tree['controller']) && isset($tree['action']))
				{
					$tree['href_text'] = $this->rUrl(array(
						'router'=>array('controller' => $tree['controller'], 'action' => $tree['action'])));
				}
				
				$srcTree[$k] = $tree;
			}
		}
		
		return $srcTree;
	}
}