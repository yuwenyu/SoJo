<?php
$ini = new Zend\Config\Reader\Ini();
$iniConfiguration = $ini->setNestSeparator('::')->fromFile(__DIR__ . '/' . 'm.ini');
return $iniConfiguration;