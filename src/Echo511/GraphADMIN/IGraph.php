<?php declare(strict_types = 1);

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
	public function getNode($label);


	/**
	 * Try to rename node. If duplicity found do NOT merge.
	 */
	public function changeNodeLabel($id, $label);


	/**
	 * Delete node by id.
	 */
	public function deleteNode($id);


	/**
	 * Change node property by id.
	 */
	public function changeNodeProperty($id, $property, $value);


	/**
	 * Create edge between nodes. Use label as reference.
	 * @return IEdge
	 */
	public function createEdge($sourceLabel, $targetLabel, $label);


	/**
	 * Change edge label.
	 */
	public function changeEdgeLabel($id, $label);


	/**
	 * Delete edge by id.
	 */
	public function deleteEdge($id);


	/**
	 * Typehint nodes by label.
	 * @return INode[]
	 */
	public function nodeTypehint($label);


	/**
	 * Return array for sigma js parser. Give only subsection of graph.
	 */
	public function sigmaJS(SigmaJS $sigmaJS, INode $node, $depth = 2): void;


	/**
	 * Export all nodes and edges in any format.
	 * @return array [format] => ..., [content] => ...
	 */
	public function export();


}
