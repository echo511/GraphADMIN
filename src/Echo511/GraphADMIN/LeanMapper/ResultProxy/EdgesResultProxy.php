<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\LeanMapper\ResultProxy;

use LeanMapper\ResultProxy;

/**
 * Result proxy when preloading Edges fore Node set.
 * @author Nikolas Tsiongas
 */
class EdgesResultProxy extends ResultProxy
{

	/** @var bool */
	private $hasPreloadedEdges = FALSE;


	/**
	 * Database was queried. Mark as preloaded.
	 */
	public function markEdgesAsPreloaded()
	{
		$this->hasPreloadedEdges = TRUE;
	}


	/**
	 * Has been database queried?
	 * @return bool
	 */
	public function hasPreloadedEdges()
	{
		return $this->hasPreloadedEdges;
	}


	/**
	 * @return string
	 */
	public static function getClass()
	{
		return get_called_class();
	}


}
