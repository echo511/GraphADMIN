<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN;

/**
 * Exporter interface.
 * @author Nikolas Tsiongas
 */
interface IExporter
{


	/**
	 * Return format of exported string.
	 * @return string
	 */
	public function getExportFormat();


	/**
	 * Export nodes and edges.
	 * @param INode[] $nodes
	 * @param IEdge[] $edges
	 * @return string
	 */
	public function export($nodes, $edges);


}
