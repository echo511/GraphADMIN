<?php

namespace Echo511\GraphADMIN\Presenters;

use Echo511\GraphADMIN\Controls\SigmaJS;
use Echo511\GraphADMIN\IGraph;
use Echo511\GraphADMIN\INode;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

/**
 * Graph presenter.
 */
class GraphPresenter extends Presenter
{

	/** @var string @persistent */
	public $nodeLabel;

	/** @var IGraph @inject */
	public $graph;

	/** @var INode */
	private $node;

	public function startup()
	{
		parent::startup();
		if ($this->nodeLabel === null) {
			$this->setView('noNode');
		} else {
			$this->node = $this->graph->getNode($this->nodeLabel);
		}
	}



	public function handleNodeTypehint($label)
	{
		$typehint = array();
		$count = -1;
		foreach ($this->graph->nodeTypehint($label) as $node) {
			$count++;
			$typehint[$count]['value'] = $node->getLabel();
		}
		$this->sendJson($typehint);
	}



	public function handleSigmaJS()
	{
		$this->sendJson($this->graph->sigmaJS($this->node));
	}



	public function handleChangeNodeLabelOrDelete()
	{
		$id = $this->getHttpRequest()->getPost('pk');
		$label = $this->getHttpRequest()->getPost('value');
		if ($label == 'DELETE!') {
			$this->graph->deleteNode($id);
			$this->nodeLabel = null;
			$this->redirect('this');
		} else {
			$this->graph->changeNodeLabel($id, $label);
			$this->nodeLabel = $label;
			$this->redirect('this');
		}
	}



	public function handleChangeNodeProperty()
	{
		$id = $this->getHttpRequest()->getPost('pk');
		$property = $this->getHttpRequest()->getPost('name');
		$value = $this->getHttpRequest()->getPost('value');
		$this->graph->changeNodeProperty($id, $property, $value);
	}



	public function handleChangeEdgeLabelOrDelete()
	{
		$id = $this->getHttpRequest()->getPost('pk');
		$label = $this->getHttpRequest()->getPost('value');
		if ($label == 'DELETE!') {
			$this->graph->deleteEdge($id);
			$this->redirect('this');
		} else {
			$this->graph->changeEdgeLabel($id, $label);
		}
	}



	public function renderNode()
	{
		$this->template->node = $this->node;
	}



	public function createComponentGetNodeForm($name)
	{
		$form = new Form($this, $name);
		$form->addText('label');
		$form->addSubmit('get');

		if ($form->isSuccess()) {
			$this->nodeLabel = $this->graph->getNode($form['label']->value)->getLabel();
			$this->redirect('this');
		}

		return $form;
	}



	public function createComponentCreateEdgeForm($name)
	{
		$form = new Form($this, $name);
		$form->addText('source');
		$form->addText('label');
		$form->addText('target');
		$form->addSubmit('create');

		if ($form->isSuccess()) {
			$this->graph->createEdge(
				$form['source']->isFilled() ? $form['source']->value : $this->node->getLabel(), $form['target']->isFilled() ? $form['target']->value : $this->node->getLabel(), $form['label']->value
			);
			$this->redirect('this');
		}

		return $form;
	}



	public function createComponentSigmajs($name)
	{
		$sigmajs = new SigmaJS($this, $name);
		$this->graph->sigmaJS($sigmajs, $this->node, 1);
		$sigmajs->onNodeColor = function ($node) {
			switch ($node->getType()) {
				case 'Artérie':
					return '#ff5151';
				case 'Kloub':
					return '#926239';
			}
		};
		return $sigmajs;
	}



}
