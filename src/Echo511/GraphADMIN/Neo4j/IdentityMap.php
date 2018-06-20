<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\Neo4j;

class IdentityMap
{

	private $map = [];

	private $uuidToId = [];


	public function load($neoId, callable $provider)
	{
		if (!\array_key_exists($neoId, $this->map)) {
			$entity = $provider($neoId);
			$this->map[$neoId] = $entity;
			$this->uuidToId[$entity->getUuid()] = $neoId;
		}
		return $this->map[$neoId];
	}


	public function loadByUuid(string $uuid, callable $provider)
	{
		if (!\array_key_exists($uuid, $this->uuidToId)) {
			$array = $provider($uuid);
			$neoId = key($array);
			$entity = \current($array);

			$this->map[$neoId] = $entity;
			$this->uuidToId[$entity->getUuid()] = $neoId;
		}
		return $this->map[$this->uuidToId[$uuid]];
	}


	public function flush()
	{
		$this->map = [];
	}


}
