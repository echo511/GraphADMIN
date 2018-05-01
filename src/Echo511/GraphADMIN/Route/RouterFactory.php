<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\Route;

class RouterFactory
{

	use \Nette\StaticClass;


	public static function createRouter(): \Nette\Application\Routers\RouteList
	{
		$router = new \Nette\Application\Routers\RouteList();
		$router[] = new \Nette\Application\Routers\Route('[<presenter>[/<action>]]', 'Graph:node');
		return $router;
	}


}
