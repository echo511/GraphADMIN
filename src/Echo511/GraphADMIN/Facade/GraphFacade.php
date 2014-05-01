<?php

namespace Echo511\GraphADMIN\Facade;

use Echo511\GraphADMIN\Controls\SigmaJS;
use Echo511\GraphADMIN\IEdgeRepository;
use Echo511\GraphADMIN\IGraph;
use Echo511\GraphADMIN\INode;
use Echo511\GraphADMIN\INodeRepository;
use Nette\Object;

class GraphFacade extends Object implements IGraph
{

	/** @var IEdgeRepository */
	private $edgeRepository;

	/** @var INodeRepository */
	private $nodeRepository;

	public function __construct(IEdgeRepository $edgeRepository, INodeRepository $nodeRepository)
	{
		$this->edgeRepository = $edgeRepository;
		$this->nodeRepository = $nodeRepository;
	}



	public function changeEdgeLabel($id, $label)
	{
		$edge = $this->edgeRepository->getById($id);
		$edge->setLabel($label);
		$this->edgeRepository->persist($edge);
	}



	public function changeNodeLabel($id, $label)
	{
		if ($this->nodeRepository->getByLabel($label) === false) {
			$node = $this->nodeRepository->getById($id);
			$node->setLabel($label);
			$this->nodeRepository->persist($node);
		}
	}



	public function changeNodeProperty($id, $property, $value)
	{
		$node = $this->nodeRepository->getById($id);
		$node->setProperty($property, $value);
		$this->nodeRepository->persist($node);
	}



	public function createEdge($sourceLabel, $targetLabel, $label)
	{
		$edge = $this->edgeRepository->createInstance();
		$edge->setSource($this->getNode($sourceLabel));
		$edge->setTarget($this->getNode($targetLabel));
		$edge->setLabel($label);
		$this->edgeRepository->persist($edge);
	}



	public function deleteEdge($id)
	{
		$edge = $this->edgeRepository->getById($id);
		if ($edge != false) {
			$this->edgeRepository->delete($edge);
		}
	}



	public function deleteNode($id)
	{
		$node = $this->nodeRepository->getById($id);
		if ($node != false) {
			$this->nodeRepository->delete($node);
		}
	}



	public function getNode($label)
	{
		$node = $this->nodeRepository->getByLabel($label);
		if ($node === false) {
			$node = $this->nodeRepository->createInstance();
			$node->setLabel($label);
			$this->nodeRepository->persist($node);
		}
		return $node;
	}



	public function nodeTypehint($label)
	{
		return $this->nodeRepository->getByLabelTypehint($label);
	}



	public function sigmaJS(SigmaJS $sigmajs, INode $node = null, $iterationLeft = 2)
	{
		$sigmajs->drawNode($node);
		if ($iterationLeft > 0) {
			$sigmajs->drawEdges($node->getEdges());
			foreach ($node->getEdges() as $edge) {
				$this->sigmaJS($sigmajs, $edge->getSource(), $iterationLeft - 1);
				$this->sigmaJS($sigmajs, $edge->getTarget(), $iterationLeft - 1);
			}
		}
	}



}
