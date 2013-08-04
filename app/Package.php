<?php

namespace Package;

use Package\Router;


class Package
{

	/**
	 * Registrace balíčku do konfigurátoru
	 *
	 * @param Nette\Configurator
	 */
	public static function register(Nette\Configurator $configurator)
	{
		$configurator->onAfter[] = function ($container) {
			// registrace rout
			$container->router[] = new Router($container);

			// registrace jmenného prostoru presenterů
			$container->presenterFactory->registerNamespace(__NAMESPACE__);
		};
	}

}
