<?php

namespace Echo511\GraphADMIN\LeanMapper\Repository;

use LeanMapper\Repository;

/**
 * Node LeanMapper internal repository.
 * @author Nikolas Tsiongas
 */
class NodeRepository extends Repository
{

	public function getById($id)
	{
		$row = $this->createFluent()->where('id = ?', $id)->fetch();

		if (!$row) {
			return false;
		}

		return $this->createEntity($row);
	}



	public function getByLabel($label)
	{
		$row = $this->createFluent()->where('label = ?', $label)->fetch();

		if (!$row) {
			return false;
		}

		return $this->createEntity($row);
	}



	public function getRandom()
	{
		$row = $this->createFluent()->orderBy('RAND()')->fetch();

		if (!$row) {
			return false;
		}

		return $this->createEntity($row);
	}



	public function getByLabelTypehint($label)
	{
		$rows = $this->createFluent()->where('label LIKE ?', '%' . implode('%', explode(" ", $label)) . '%')->fetchAll();
		return $this->createEntities($rows);
	}



}
