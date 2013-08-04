<?php

namespace Package;

use Package\Router;
use Nette;


class Package
{

	/**
	 * Registrace balíčku do konfigurátoru
	 *
	 * @param Nette\Configurator
	 */
	public static function register(Nette\Configurator $configurator)
	{
		/** @var Nette\DI\Container $container */
		$configurator->onAfter[] = function ($container) {
			// registrace rout
			$container->router[] = new Router($container);

			// registrace jmenného prostoru presenterů
			$container->presenterFactory->registerNamespace(__NAMESPACE__);
		};
	}

}
