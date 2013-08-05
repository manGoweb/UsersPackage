<?php

namespace Package;

use Package\Router;
use Nette\Configurator;
use Nette\DI\Container;


class Package
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
			$container->router[] = new Router($container);

			// registrace jmenného prostoru presenterů
			$container->getService('nette.presenterFactory')->registerNamespace(__NAMESPACE__);

			// registrace repositářů
			//self::registerRepository($container, 'name', __NAMESPACE__ . '\\RepositoryClass');

			// registrace služeb
			//self::registerService($container, 'name', 'Service\\Class\\Name'); // třída
			//self::registerService($container, 'name', $container->createInstance('Service\\Class\\Name')); // autowiring
		};
	}

	/**
	 * Registruje repozitář do ORM containeru
	 */
	public static function registerRepository(Container $container, $name, $class)
	{
		if (!$container->orm->isRepository($name))
		{
			$container->orm->register($name, $class);
		}
	}

	/**
	 * Registruje službu do DI containeru
	 */
	public static function registerService(Container $container, $name, $service)
	{
		if (!$container->hasService($name))
		{
			$container->addService($name, $service);
		}
	}

}
