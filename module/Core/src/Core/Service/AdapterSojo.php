<?php
/**
 * @namespace Core
 *
 * @author Kyle Yu
 * @copyright Copyright (c) 2015~2***
 */

namespace Core\Service;

use Zend\Db\Adapter\Adapter;

class AdapterSojo extends Adapter
{
	public function __construct($driver)
	{
		parent::__construct($driver);
	}
}