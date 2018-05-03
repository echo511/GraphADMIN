<?php declare(strict_types = 1);

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

	/** @var int @persistent */
	public $sigmaJSDepth = 5;

	/** @var string|NULL @persistent */
	public $nodeLabel;

	/** @var IGraph @inject */
	public $graph;

	/** @var INode */
	private $node;

	/** @var \Echo511\GraphADMIN\Facade\GraphFacade */
	private $graphFacade;

	/** @var \MySQLDump */
	private $dumper;


	public function __construct(
		\Echo511\GraphADMIN\Facade\GraphFacade $graphFacade,
		\MySQLDump $dumper
	) {
		parent::__construct();
		$this->graphFacade = $graphFacade;
		$this->dumper = $dumper;
	}


	public function startup()
	{
		parent::startup();
		if ($this->nodeLabel === NULL) {
			$this->setView('noNode');
		} else {
			$this->node = $this->graph->getNode($this->nodeLabel);
		}
	}


	/**
	 * Response with JSON for Twitter's typeahead.
	 */
	public function handleNodeTypehint($label): void
	{
		$typehint = [];
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
	public function handleChangeNodeLabelOrDelete(): void
	{
		$id = $this->getHttpRequest()->getPost('pk');
		$label = $this->getHttpRequest()->getPost('value');
		if ($label == 'DELETE!') {
			$this->graph->deleteNode($id);
			$this->nodeLabel = NULL;
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
	public function handleChangeNodeProperty(): void
	{
		$id = $this->getHttpRequest()->getPost('pk');
		$property = $this->getHttpRequest()->getPost('name');
		$value = $this->getHttpRequest()->getPost('value');
		$this->graph->changeNodeProperty($id, $property, $value);
	}


	/**
	 * Process X-editable call.
	 */
	public function handleChangeEdgeLabelOrDelete(): void
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
		\ob_start();
		$this->dumper->write();
		$content = \ob_get_clean();
		$name = date('Y-m-d-H-i-s') . '_' . $this->getHttpRequest()->getUrl()->getHost() . '_graph-dump.sql';
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
	 */
	public function createComponentGetNodeForm(): Form
	{
		$form = new Form();
		$form->addText('label');
		$form->addSubmit('get');

		$form->onSuccess[] = function (Form $form) {
			$this->nodeLabel = $this->graph->getNode($form['label']->value)->getLabel();
			$this->redirect('this');
		};

		return $form;
	}


	/**
	 * Create edge between two nodes.
	 */
	public function createComponentCreateEdgeForm(): Form
	{
		$form = new Form();
		$form->addText('source');
		$form->addText('label');
		$form->addText('target');
		$form->addSubmit('create');

		$form->onSuccess[] = function (Form $form) {
			$this->graph->createEdge(
				$form['source']->isFilled() ? $form['source']->value : $this->node->getLabel(),
				$form['target']->isFilled() ? $form['target']->value : $this->node->getLabel(),
				$form['label']->value
			);
			$this->redirect('this');
		};

		return $form;
	}


	public function createComponentSigmajs(): SigmaJS
	{
		$sigmaJS = new SigmaJS();
		$node = $this->node ?? $this->graphFacade->getRandomNode();
		$this->graph->sigmaJS($sigmaJS, $node, $this->sigmaJSDepth);
		$sigmaJS->onNodeColor[] = function (INode $node, $formatting): void {
			if (!$formatting instanceof \Echo511\GraphADMIN\Controls\Formatting) {
				throw new \InvalidArgumentException();
			}

			if (\Nette\Utils\Strings::startsWith($node->getLabel(), 'Nc')) {
				$formatting->setColor('#f47142');
			}

			switch ($node->getType()) {
				case 'Artérie':
					$formatting->setColor('#ff5151');
					break;
				case 'Kloub':
					$formatting->setColor('#926239');
					break;
			}

			if ($node->getLabel() === $this->nodeLabel) {
				$formatting->setColor('#f44283');
			}
		};
		return $sigmaJS;
	}


	public function createComponentSetSigmaJSDepthForm()
	{
		$form = new Form();
		$form->addInteger('depth', 'Hloubka grafu')
			->setDefaultValue($this->sigmaJSDepth)
			->setRequired(TRUE);
		$form->addSubmit('set');
		$form->onSuccess[] = function (Form $form, array $values) {
			$this->sigmaJSDepth = $values['depth'];
			$this->redirect('this');
		};
		return $form;
	}


}
