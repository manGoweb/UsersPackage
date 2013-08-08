<?php

namespace Clevis\Users;

use Nette\DI\Container;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


/**
 * Router balíčku
 */
class Router extends RouteList
{

	public function __construct($module = 'Package', $prefix = 'package')
	{
		parent::__construct($module);

		// úvodní stránka
		$this[] = new Route($prefix, 'Home:default');
	}

}
