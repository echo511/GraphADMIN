<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\Neo4j;

class GraphFacade
{

	/**
	 * @var \GraphAware\Neo4j\Client\Client
	 */
	private $client;

	/**
	 * @var \Echo511\GraphADMIN\Neo4j\IdentityMap
	 */
	private $identityMap;


	public function __construct(\GraphAware\Neo4j\Client\Client $client, \Echo511\GraphADMIN\Neo4j\IdentityMap $identityMap)
	{
		$this->client = $client;
		$this->identityMap = $identityMap;
	}


	public function createNode(string $label): Node
	{
		$result = $this->client->run('CREATE (n:NODE {n}) RETURN n', [
			'n' => [
				'uuid' => $this->generateUuid(),
				'label' => $label,
			],
		]);
		$neoNode = $result->firstRecord()->nodeValue('n');
		return $this->instantiateNode($neoNode);
	}


	public function fetchNodeByLabel(string $label): ?Node
	{
		$result = $this->client->run('MATCH (n {label: {n}.label}) RETURN n', [
			'n' => ['label' => $label]
		]);

		$record = $result->firstRecord();

		if (!$record) {
			return NULL;
		}

		$neoNode = $record->nodeValue('n');
		return $this->instantiateNode($neoNode);
	}


	public function fetchNode(string $uuid): Node
	{
		return $this->identityMap->loadByUuid($uuid, function ($uuid) {
			$result = $this->client->run('MATCH (n {uuid: {n}.uuid}) RETURN n', [
				'n' => ['uuid' => $uuid],
			]);
			$neoNode = $result->firstRecord()->nodeValue('n');
			return [$neoNode->identity() => $this->instantiateNode($neoNode)];
		});
	}


	public function fetchNodeById($id): Node
	{
		if (! \is_int($id)) {
			throw new \InvalidArgumentException('ID has to be an integer.');
		}
		return $this->identityMap->load($id, function ($neoId) {
			$result = $this->client->run(
				'START s=NODE(' . $neoId . ') MATCH (s) RETURN s'
			);
			$neoNode = $result->firstRecord()->nodeValue('s');
			return $this->instantiateNode($neoNode);
		});
	}


	public function fetchEdge(string $uuid): Edge
	{
		return $this->identityMap->load($uuid, function () use ($uuid) {
			$result = $this->client->run('MATCH ()-[r {uuid: {r}.uuid}]-() RETURN r', [
				'r' => ['uuid' => $uuid],
			]);
			$graphRelationship = $result->firstRecord()->relationshipValue('r');
			return $this->instantiateEdge($graphRelationship);
		});
	}


	public function createEdge($sourceUuid, $targetUuid, $label)
	{
		$this->client->run('MATCH (s {uuid: {s}.uuid}), (t {uuid: {t}.uuid}) CREATE (s)-[r:REL {r}]->(t)', [
			's' => ['uuid' => $sourceUuid],
			't' => ['uuid' => $targetUuid],
			'r' => [
				'uuid' => $this->generateUuid(),
				'label' => $label,
			],
		]);
	}


	public function setNodeProperty($uuid, $property, $value)
	{
		$this->client->run('MATCH (n {uuid: {n}.uuid}) SET n += {properties}', [
			'n' => ['uuid' => $uuid],
			'properties' => [$property => $value],
		]);
	}


	public function setEdgeProperty($uuid, $property, $value)
	{
		$this->client->run('MATCH ()-[r {uuid: {r}.uuid}]-() SET r += {properties}', [
			'r' => ['uuid' => $uuid],
			'properties' => [$property => $value],
		]);
	}


	public function getRelationships(string $uuid)
	{
		$result = $this->client->run('MATCH (n {uuid: {n}.uuid})-[r]-() RETURN r', [
			'n' => ['uuid' => $uuid],
		]);

		$edges = [];
		foreach ($result->records() as $record) {
			$neoRelationship = $record->relationshipValue('r');
			$edges[] = $this->instantiateEdge($neoRelationship);
		}
		return $edges;
	}


	public function deleteNode($uuid)
	{
		$this->client->run('MATCH (n {uuid: {n}.uuid}) DELETE n', [
			'n' => ['uuid' => $uuid],
		]);
	}


	public function deleteEdge($uuid)
	{
		$this->client->run('MATCH ()-[r {uuid: {r}.uuid}]-() DELETE r', [
			'r' => ['uuid' => $uuid],
		]);
	}


	public function typeHint($label)
	{
		$hints = [];
		$result = $this->client->run(
			'MATCH (n) WHERE n.label =~ \'.*' . $label . '.*\' RETURN n'
		);
		foreach ($result->records() as $record) {
			$graphNode = $record->nodeValue('n');
			$hints[] = $this->fetchNode($graphNode->get('uuid'));
		}
		return $hints;
	}


	public function redirectEdge(
		string $edgeUuid,
		string $newNodeUuid,
		bool $redirectingSource = TRUE
	) {
//		$transaction = $this->client->transaction();
//
//		// 1) fetch relationship via uuid
//		$result = $transaction->run('MATCH ()-[r {r}]-() RETURN r', [
//			'r' => ['uuid' => $edgeUuid],
//		]);
//
//		$record = $result->firstRecord();
//		$relationship = $record->relationshipValue('r');
//
//		// 2) mark current points
//		$startNode = $relationship->getStartNode();
//		$endNode = $relationship->getEndNode();
//
//		// 3) create new relationship
//		if ($redirectingSource) {
//			$startNodeUuid = $newNodeUuid;
//			$endNodeUuid = $endNode->get('uuid');
//		} else {
//			$startNodeUuid = $startNode->get('uuid');
//			$endNode = $newNodeUuid;
//		}
//
//		$transaction->push('
//			MATCH (s {s}), (e {e})
//			CREATE (s)-[nr:REL {nr}]->(e)
//		', [
//			's' => ['uuid' => $startNodeUuid],
//			'e' => ['uuid' => $endNodeUuid],
//			'nr' => $relationship->getProperties(),
//		]);
//
//		// 4) delete old relationship
//		$transaction->push('MATCH ()-[r {r}]-() DELETE r', [
//			'r' => ['uuid' => $edgeUuid]
//		]);
//
//		$transaction->commit();
//
//		$this->identityMap->flush();
	}


	private function instantiateNode(\GraphAware\Common\Type\NodeInterface $node): Node
	{
		return new Node($node, $this);
	}


	private function instantiateEdge(\GraphAware\Common\Type\RelationshipInterface $relationship): Edge
	{
		return new Edge($relationship, $this);
	}


	private function generateUuid()
	{
		$uuid = \Ramsey\Uuid\Uuid::uuid4();
		return $uuid->toString();
	}


}
