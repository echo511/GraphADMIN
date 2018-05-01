<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\Backup;

use Echo511\GraphADMIN\IEdge;
use Echo511\GraphADMIN\IExporter;
use Echo511\GraphADMIN\INode;

/**
 * Export given nodes and edges as json.
 * @author Nikolas Tsiongas
 */
class JsonExporter implements IExporter
{


	/**
	 * @param INode[] $nodes
	 * @param IEdge[] $edges
	 */
	public function export($nodes, $edges)
	{
		$result = [];
		foreach ($nodes as $node) {
			$result['nodes'][$node->getId()]['id'] = $node->getId();
			$result['nodes'][$node->getId()]['label'] = $node->getLabel();
			$result['nodes'][$node->getId()]['type'] = $node->getType();
			foreach ($node->getProperties() as $property => $value) {
				$result['nodes'][$node->getId()]['properties'][$property] = $value;
			}
		}

		foreach ($edges as $edge) {
			$result['edges'][$edge->getId()]['id'] = $edge->getId();
			$result['edges'][$edge->getId()]['label'] = $edge->getLabel();
			$result['edges'][$edge->getId()]['source'] = $edge->getSource()->getId();
			$result['edges'][$edge->getId()]['target'] = $edge->getTarget()->getId();
			$result['edges'][$edge->getId()]['type'] = $edge->getType();
		}
		return json_encode($result);
	}


	public function getExportFormat()
	{
		return 'json';
	}


}
