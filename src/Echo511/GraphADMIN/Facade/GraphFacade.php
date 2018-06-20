<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\Facade;

use Echo511\GraphADMIN\Controls\SigmaJS;
use Echo511\GraphADMIN\IEdgeRepository;
use Echo511\GraphADMIN\IExporter;
use Echo511\GraphADMIN\IGraph;
use Echo511\GraphADMIN\INode;
use Echo511\GraphADMIN\INodeRepository;

/**
 * Facade for GraphPresenter.
 * @author Nikolas Tsiongas
 */
class GraphFacade implements IGraph
{

	/** @var IEdgeRepository */
	private $edgeRepository;

	/** @var INodeRepository */
	private $nodeRepository;

	/** @var IExporter */
	private $exporter;


	/**
	 * @param IEdgeRepository $edgeRepository
	 * @param INodeRepository $nodeRepository
	 */
	public function __construct(IEdgeRepository $edgeRepository, INodeRepository $nodeRepository, IExporter $exporter)
	{
		$this->edgeRepository = $edgeRepository;
		$this->nodeRepository = $nodeRepository;
		$this->exporter = $exporter;
	}


	public function changeEdgeLabel($id, $label)
	{
		$edge = $this->edgeRepository->getById($id);
		$edge->setLabel($label);
		$this->edgeRepository->persist($edge);
	}


	public function changeNodeLabel($id, $label)
	{
		if ($this->nodeRepository->getByLabel($label) === FALSE) {
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
		if ($edge != FALSE) {
			$this->edgeRepository->delete($edge);
		}
	}


	public function deleteNode($id)
	{
		$node = $this->nodeRepository->getById($id);
		if ($node != FALSE) {
			$this->nodeRepository->delete($node);
		}
	}


	public function getNode($label)
	{
		$node = $this->nodeRepository->getByLabel($label);
		if ($node === FALSE) {
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


	public function sigmaJS(SigmaJS $sigmaJS, INode $node, $depth = 2, array & $drawnNodesIdsMap = []): void
	{
		if (!\array_key_exists($node->getUuid(), $drawnNodesIdsMap)) {
			$drawnNodesIdsMap[$node->getUuid()] = TRUE;
			$sigmaJS->drawNode($node);
			if ($depth > 0) {
				$sigmaJS->drawEdges($node->getEdges());
				foreach ($node->getEdges() as $edge) {
					$this->sigmaJS($sigmaJS, $edge->getSource(), $depth - 1, $drawnNodesIdsMap);
					$this->sigmaJS($sigmaJS, $edge->getTarget(), $depth - 1, $drawnNodesIdsMap);
				}
			}
		}
	}


	public function export()
	{
		return [
			'format' => $this->exporter->getExportFormat(),
			'content' => $this->exporter->export($this->nodeRepository->getAll(), $this->edgeRepository->getAll())
		];
	}


	public function getRandomNode()
	{
		return $this->nodeRepository->getRandom();
	}


}
