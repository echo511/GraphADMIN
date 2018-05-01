<?php declare(strict_types = 1);

namespace Echo511\GraphADMIN\Controls;

class Formatting
{

	/** @var string|NULL */
	private $color;


	/**
	 * @return NULL|string
	 */
	public function getColor(): ?string
	{
		return $this->color;
	}


	/**
	 * @param NULL|string $color
	 */
	public function setColor(?string $color): void
	{
		$this->color = $color;
	}


}
