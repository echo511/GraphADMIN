<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\LeanMapper\Repository;

use LeanMapper\Repository;

/**
 * Edge LeanMapper internal repository.
 * @author Nikolas Tsiongas
 */
class EdgeRepository extends Repository
{


	public function getAll()
	{
		$rows = $this->createFluent()->fetchAll();
		return $this->createEntities($rows);
	}


	public function getById($id)
	{
		$row = $this->createFluent()->where('id = ?', $id)->fetch();

		if (!$row) {
			return FALSE;
		}

		return $this->createEntity($row);
	}


}
