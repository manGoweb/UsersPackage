<?php

namespace Package; // todo: změň při vytvoření balíčku

use Package\Router; // todo: změň při vytvoření balíčku
use Nette\Configurator;
use Nette\DI\Container;


/**
 * Registrátor balíčku
 */
class Package extends \Clevis\Skeleton\Package
{

	/**
	 * Registrace balíčku do konfigurátoru
	 *
	 * @param Configurator
	 */
	public static function register(Configurator $configurator)
	{
		/** @var Container $container */
		$configurator->onAfter[] = function (Container $container) {

			// registrace rout
			$container->router[] = new Router;

			// registrace jmenného prostoru presenterů
			$container->getService('nette.presenterFactory')->registerNamespace(__NAMESPACE__);

			// registrace repositářů
			//self::registerRepository($container, 'name', __NAMESPACE__ . '\\RepositoryClass');

			// registrace služeb
			//self::registerService($container, 'name', new Service\Class\Name); // služba
			//self::registerService($container, 'name', $container->createInstance('Service\\Class\\Name')); // autowiring
		};
	}

}
