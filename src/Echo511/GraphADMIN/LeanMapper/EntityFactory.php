<?php

namespace Echo511\GraphADMIN\LeanMapper;

use LeanMapper\Entity;
use LeanMapper\IEntityFactory;
use LeanMapper\Row;
use Nette\DI\Container;
use Nette\Object;

/**
 * Create entity via Nette container.
 * @author Nikolas Tsiongas
 */
class EntityFactory extends Object implements IEntityFactory
{

	/** @var Container */
	private $container;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}



	/**
	 * @param Entity[] $entities
	 * @return array
	 */
	public function createCollection(array $entities)
	{
		return $entities;
	}



	/**
	 * @param string $entityClass
	 * @param Row|null $arg
	 * @return Entity
	 */
	public function createEntity($entityClass, $arg = null)
	{
		return $this->container->createInstance($entityClass, !is_array($arg) && !is_null($arg) ? array($arg) : $arg);
	}



}
