<?php
namespace Shikoru\BulbapediaSetScraper\Objects;
class Card
{
	public $name, $number, $type, $rarity, $evolutionStage, $hp, $weakness, $resistance, $retreatCost, $illustrator, $attacks, $abilities, $alternateAbilities, $finishes, $hasPromo, $promoText, $promoType;

	public function __construct()
	{
		$this->attacks = [];
		$this->finishes = [];
		$this->abilities = [];
		$this->alternateAbilities = [];

	}


	public function setType(string $type)
	{
		$this->type = $type;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setRarity(string $rarity)
	{
		$this->rarity = $rarity;
	}

	public function getRarity()
	{
		return $this->rarity;
	}

	public function setEvolutiuonStage(string $evolutionStage)
	{
		$this->evolutionStage = $evolutionStage;
	}

	public function getEvolutiuonStage()
	{
		return $this->evolutionStage;
	}

	public function setHp(int $hp)
	{
		$this->hp = $hp;
	}

	public function getHp()
	{
		return $this->hp;
	}
}