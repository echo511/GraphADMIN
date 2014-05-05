<?php

namespace Echo511\GraphADMIN\Presenters;

use Echo511\GraphADMIN\Controls\SigmaJS;
use Echo511\GraphADMIN\IGraph;
use Echo511\GraphADMIN\INode;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

/**
 * Graph presenter.
 * @author Nikolas Tsiongas
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



	/**
	 * Response with JSON for Twitter's typeahead.
	 * @param string $label
	 */
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



	/**
	 * Process X-editable call.
	 */
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



	/**
	 * Process X-editable call.
	 */
	public function handleChangeNodeProperty()
	{
		$id = $this->getHttpRequest()->getPost('pk');
		$property = $this->getHttpRequest()->getPost('name');
		$value = $this->getHttpRequest()->getPost('value');
		$this->graph->changeNodeProperty($id, $property, $value);
	}



	/**
	 * Process X-editable call.
	 */
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



	public function handleExportAll()
	{
		$export = $this->graph->export();
		$name = date('Y-m-d-H-i-s') . '_' . $this->getHttpRequest()->getUrl()->getHost() . '_graph-dump.' . $export['format'];
		$content = $export['content'];

		$this->getHttpResponse()->setContentType('text/plain');
		$this->getHttpResponse()->setHeader('Content-Disposition', 'attachment; filename="' . $name . '"');
		$this->getHttpResponse()->setHeader('Content-Length', strlen($content));
		$this->sendResponse(new TextResponse($content));
	}



	public function renderNode()
	{
		$this->template->node = $this->node;
	}



	/**
	 * Search or create node.
	 * @param string $name
	 * @return Form
	 */
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



	/**
	 * Create edge between two nodes.
	 * @param string $name
	 * @return Form
	 */
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



	/**
	 * Render SigmaJS graph
	 * @param string $name
	 * @return SigmaJS
	 */
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
