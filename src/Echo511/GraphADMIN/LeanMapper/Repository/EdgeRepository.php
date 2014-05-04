<?php

namespace Echo511\GraphADMIN\LeanMapper\Repository;

use LeanMapper\Repository;

/**
 * Edge LeanMapper internal repository.
 * @author Nikolas Tsiongas
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
