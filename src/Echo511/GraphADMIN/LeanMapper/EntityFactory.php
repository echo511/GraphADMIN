<?php

namespace Echo511\GraphADMIN\LeanMapper;

use LeanMapper\IEntityFactory;
use Nette\DI\Container;
use Nette\Object;

class EntityFactory extends Object implements IEntityFactory
{

	/** @var Container */
	private $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}



	public function createCollection(array $entities)
	{
		return $entities;
	}



	public function createEntity($entityClass, $arg = null)
	{
		return $this->container->createInstance($entityClass, !is_array($arg) && !is_null($arg) ? array($arg) : $arg);
	}



}
