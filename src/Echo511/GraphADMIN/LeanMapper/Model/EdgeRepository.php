<?php

namespace Echo511\GraphADMIN\LeanMapper\Model;

use Echo511\GraphADMIN\IEdge;
use Echo511\GraphADMIN\IEdgeRepository;
use Echo511\GraphADMIN\LeanMapper\EntityFactory;
use Echo511\GraphADMIN\LeanMapper\Repository\EdgeRepository as LMEdgeRepository;
use Nette\Object;

/**
 * Edge repository. Proxy class for LeanMapper internal repository.
 * @author Nikolas Tsiongas
 */
class EdgeRepository extends Object implements IEdgeRepository
{

	/** @var EntityFactory */
	private $entityFactory;

	/** @var LMEdgeRepository */
	private $repository;

	/**
	 * @param EdgeRepository $repository
	 * @param EntityFactory $entityFactory
	 */
	public function __construct(LMEdgeRepository $repository, EntityFactory $entityFactory)
	{
		$this->entityFactory = $entityFactory;
		$this->repository = $repository;
	}



	public function createInstance()
	{
		return $this->entityFactory->createEntity('Echo511\GraphADMIN\LeanMapper\Model\Edge');
	}



	public function getAll()
	{
		return $this->repository->getAll();
	}



	public function getById($id)
	{
		return $this->repository->getById($id);
	}



	public function persist(IEdge $edge)
	{
		$this->repository->persist($edge);
	}



	public function delete(IEdge $edge)
	{
		$this->repository->delete($edge);
	}



}
