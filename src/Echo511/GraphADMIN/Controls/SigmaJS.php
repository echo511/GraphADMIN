<?php

namespace Echo511\GraphADMIN\Controls;

use Echo511\GraphADMIN\IEdge;
use Echo511\GraphADMIN\INode;
use Nette\Application\UI\Control;

class SigmaJS extends Control
{

	/** @var array */
	public $onNodeColor;

	/** @var array */
	public $onEdgeColor;

	/** @var INode[] */
	private $nodes = array();

	/** @var IEdge[] */
	private $edges = array();

	public function drawNode(INode $node)
	{
		$this->nodes[$node->id] = $node;
	}



	public function drawNodes($nodes)
	{
		foreach ($nodes as $node) {
			$this->drawNode($node);
		}
	}



	public function drawEdge(IEdge $edge)
	{
		$this->edges[$edge->getId()] = $edge;
	}



	public function drawEdges($edges)
	{
		foreach ($edges as $edge) {
			$this->drawEdge($edge);
		}
	}



	public function handleGetData()
	{
		$result['nodes'] = array();
		$count = -1;
		foreach ($this->nodes as $key => $node) {
			$count++;
			$result['nodes'][$count]['id'] = (string) $node->getId();
			$result['nodes'][$count]['label'] = $node->getLabel();
			$result['nodes'][$count]['x'] = $count % 10;
			$result['nodes'][$count]['y'] = floor($count / 10);
			$result['nodes'][$count]['size'] = $this->getNodeSize($node);
			$result['nodes'][$count]['color'] = $this->getNodeColor($node);
		}

		$result['edges'] = array();
		$count = -1;
		foreach ($this->edges as $key => $edge) {
			$count++;
			$result['edges'][$count]['id'] = (string) $edge->getId();
			$result['edges'][$count]['source'] = (string) $edge->getSource()->getId();
			$result['edges'][$count]['target'] = (string) $edge->getTarget()->getId();
			$result['edges'][$count]['color'] = $this->getEdgeColor($edge);
		}

		$this->getPresenter()->sendJson($result);
	}



	public function render()
	{
		$this->template->setFile(__DIR__ . '/sigmajs.latte');
		$this->template->name = $this->name;
		$this->template->render();
	}



	private function getNodeSize(INode $node)
	{
		return 1;
	}



	private function getNodeColor(INode $node)
	{
		$color = $this->onNodeColor($node);
		return $color !== null ? $color : '#fff';
	}



	private function getEdgeColor(IEdge $edge)
	{
		$color = $this->onEdgeColor($edge);
		return $color !== null ? $color : '#fff';
	}



}
