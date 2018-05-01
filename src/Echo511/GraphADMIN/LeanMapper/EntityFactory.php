<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\LeanMapper;

use LeanMapper\Entity;
use LeanMapper\IEntityFactory;
use LeanMapper\Row;
use Nette\DI\Container;

/**
 * Create entity via Nette container.
 * @author Nikolas Tsiongas
 */
class EntityFactory  implements IEntityFactory
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
	 * @param Row|array|null $arg
	 * @return Entity
	 */
	public function createEntity($entityClass, $arg = NULL)
	{
		return $this->container->createInstance($entityClass, !is_array($arg) ? [$arg] : $arg);
	}


}
