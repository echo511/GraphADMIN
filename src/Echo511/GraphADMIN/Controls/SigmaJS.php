<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\Controls;

use Echo511\GraphADMIN\IEdge;
use Echo511\GraphADMIN\INode;
use Nette\Application\UI\Control;

/**
 * Render SigmaJS graph.
 * @author Nikolas Tsiongas
 */
class SigmaJS extends Control
{

	/**
	 * Callback.
	 * Return color hex. Argument INode.
	 * @var callable[]
	 */
	public $onNodeColor = [];

	/**
	 * Callback.
	 * Return color hex. Argument IEdge.
	 * @var callable[]
	 */
	public $onEdgeColor = [];

	/** @var INode[] */
	private $nodes = [];

	/** @var IEdge[] */
	private $edges = [];


	/**
	 * Mark node for rendering.
	 * @param INode $node
	 */
	public function drawNode(INode $node)
	{
		$this->nodes[$node->getId()] = $node;
	}


	/**
	 * Mark nodes for rendering.
	 * @param INode[] $nodes
	 */
	public function drawNodes($nodes)
	{
		foreach ($nodes as $node) {
			$this->drawNode($node);
		}
	}


	/**
	 * Mark edge for rendering.
	 * @param IEdge $edge
	 */
	public function drawEdge(IEdge $edge)
	{
		$this->edges[$edge->getId()] = $edge;
	}


	/**
	 * Mark edges for rendering.
	 * @param IEdge[] $edges
	 */
	public function drawEdges($edges)
	{
		foreach ($edges as $edge) {
			$this->drawEdge($edge);
		}
	}


	/**
	 * Respond with JSON to fill sigma graph.
	 *
	 * Note: $prev is used so SigmaJS force engine renders nodes at the same coordinates
	 * each time.
	 */
	public function handleGetData()
	{
		$result['nodes'] = [];
		$count = -1;
		$prev = NULL;
		foreach ($this->nodes as $key => $node) {
			$prev = $prev ?? $node;
			$count++;
			$result['nodes'][$count]['id'] = (string) $node->getId();
			$result['nodes'][$count]['label'] = $node->getLabel();
			$result['nodes'][$count]['x'] = $node->getId();
			$result['nodes'][$count]['y'] = $prev->getId();
			$result['nodes'][$count]['size'] = $this->getNodeSize($node);
			$result['nodes'][$count]['color'] = $this->getNodeColor($node);
			$prev = $node;
		}

		$result['edges'] = [];
		$count = -1;
		foreach ($this->edges as $key => $edge) {
			$count++;
			$result['edges'][$count]['id'] = (string) $edge->getId();
			$result['edges'][$count]['source'] = (string) $edge->getSource()->getId();
			$result['edges'][$count]['target'] = (string) $edge->getTarget()->getId();
			$result['edges'][$count]['color'] = $this->getEdgeColor($edge);
			$result['edges'][$count]['label'] = $edge->getLabel();
		}

		$this->getPresenter()->sendJson($result);
	}


	public function handleRedirectLabel()
	{
		$presenter = $this->getPresenter();
		$label = $presenter->getParameter('label');
		$link = $presenter->link('Graph:node', ['nodeLabel' => $label]);
		$presenter->getPayload()->redirect = $link;
		$presenter->sendPayload();
	}


	/**
	 * Render component.
	 */
	public function render()
	{
		$this->template->setFile(__DIR__ . '/sigmajs.latte');
		$this->template->name = $this->name;
		$this->template->render();
	}


	/**
	 * @param INode $node
	 * @return int
	 */
	private function getNodeSize(INode $node)
	{
		return 1;
	}


	/**
	 * @param INode $node
	 * @return string
	 */
	private function getNodeColor(INode $node)
	{
		$formatting = new Formatting();
		$formatting->setColor('#fff');
		$this->onNodeColor($node, $formatting);
		return $formatting->getColor();
	}


	/**
	 * @param IEdge $edge
	 * @return string
	 */
	private function getEdgeColor(IEdge $edge)
	{
		$formatting = new Formatting();
		$formatting->setColor('#fff');
		$this->onEdgeColor($edge, $formatting);
		return $formatting->getColor();
	}


}
