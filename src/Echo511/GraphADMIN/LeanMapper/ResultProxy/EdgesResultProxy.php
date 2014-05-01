<?php

namespace Echo511\GraphADMIN\LeanMapper\ResultProxy;

use LeanMapper\ResultProxy;

class EdgesResultProxy extends ResultProxy
{

	/** @var bool */
	private $hasPreloadedEdges = false;

	public function markEdgesAsPreloaded()
	{
		$this->hasPreloadedEdges = true;
	}



	public function hasPreloadedEdges()
	{
		return $this->hasPreloadedEdges;
	}



	public static function getClass()
	{
		return get_called_class();
	}



}
