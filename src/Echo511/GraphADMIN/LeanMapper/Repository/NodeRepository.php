<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\LeanMapper\Repository;

use LeanMapper\Repository;

/**
 * Node LeanMapper internal repository.
 * @author Nikolas Tsiongas
 */
class NodeRepository extends Repository
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


	public function getByLabel($label)
	{
		$row = $this->createFluent()->where('label = ?', $label)->fetch();

		if (!$row) {
			return FALSE;
		}

		return $this->createEntity($row);
	}


	public function getRandom()
	{
		$row = $this->createFluent()->orderBy('RAND()')->fetch();

		if (!$row) {
			return FALSE;
		}

		return $this->createEntity($row);
	}


	public function getByLabelTypehint($label)
	{
		$rows = $this->createFluent()->where('label LIKE ?', '%' . implode('%', explode(" ", $label)) . '%')->fetchAll();
		return $this->createEntities($rows);
	}


}
