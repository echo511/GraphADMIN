<?php

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
	function getExportFormat();

	/**
	 * Export nodes and edges.
	 * @param INode[] $nodes
	 * @param IEdge[] $edges
	 * @return string
	 */
	function export($nodes, $edges);
}
