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
			$result['nodes'][$node->getUuid()]['id'] = $node->getUuid();
			$result['nodes'][$node->getUuid()]['label'] = $node->getLabel();
			$result['nodes'][$node->getUuid()]['type'] = $node->getType();
			foreach ($node->getProperties() as $property => $value) {
				$result['nodes'][$node->getUuid()]['properties'][$property] = $value;
			}
		}

		foreach ($edges as $edge) {
			$result['edges'][$edge->getUuid()]['id'] = $edge->getUuid();
			$result['edges'][$edge->getUuid()]['label'] = $edge->getLabel();
			$result['edges'][$edge->getUuid()]['source'] = $edge->getSource()->getUuid();
			$result['edges'][$edge->getUuid()]['target'] = $edge->getTarget()->getUuid();
			$result['edges'][$edge->getUuid()]['type'] = $edge->getType();
		}
		return json_encode($result);
	}


	public function getExportFormat()
	{
		return 'json';
	}


}
