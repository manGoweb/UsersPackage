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
			//self::registerService($container, 'name', new Service\Class\Name); // služba
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
		static $param;

		if (!$container->hasService($name))
		{
			// black magic!
			if (!$param)
			{
				$ref = new \ReflectionClass($container);
				$param = $ref->getProperty('meta');
				$param->setAccessible(TRUE);
			}
			$meta = $param->getValue($container);

			$class = get_class($service);
			foreach (class_parents($class) + class_implements($class) + array($class) as $parent) {
				$parent = strtolower($parent);
				if (empty($meta[Container::TYPES][$parent]) || !in_array($name, $meta[Container::TYPES][$parent]))
				{
					$meta[Container::TYPES][$parent][] = (string) $name;
				}
			}
			$param->setValue($container, $meta);

			$container->addService($name, $service);
		}
	}

}
