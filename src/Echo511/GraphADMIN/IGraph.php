<?php

namespace Echo511\GraphADMIN;

use Echo511\GraphADMIN\Controls\SigmaJS;

/**
 * Graph facade interface.
 * @author Nikolas Tsiongas
 */
interface IGraph
{

	/**
	 * Return node by label. If node does not exist create it.
	 * @return INode
	 */
	function getNode($label);

	/**
	 * Try to rename node. If duplicity found do NOT merge.
	 */
	function changeNodeLabel($id, $label);

	/**
	 * Delete node by id.
	 */
	function deleteNode($id);

	/**
	 * Change node property by id.
	 */
	function changeNodeProperty($id, $property, $value);

	/**
	 * Create edge between nodes. Use label as reference.
	 * @return IEdge
	 */
	function createEdge($sourceLabel, $targetLabel, $label);

	/**
	 * Change edge label.
	 */
	function changeEdgeLabel($id, $label);

	/**
	 * Delete edge by id.
	 */
	function deleteEdge($id);

	/**
	 * Typehint nodes by label.
	 * @return INode[]
	 */
	function nodeTypehint($label);

	/**
	 * Return array for sigma js parser. Give only subsection of graph.
	 * If node is null then select random.
	 * @return array
	 */
	function sigmaJS(SigmaJS $sigmajs, INode $node = null, $depth = 2);
}
