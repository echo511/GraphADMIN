<?php

namespace Echo511\GraphADMIN\LeanMapper\Repository;

use LeanMapper\Repository;

/**
 */
class EdgeRepository extends Repository
{

	public function getById($id)
	{
		$row = $this->createFluent()->where('id = ?', $id)->fetch();

		if (!$row) {
			return false;
		}

		return $this->createEntity($row);
	}



}
