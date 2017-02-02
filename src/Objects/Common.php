<?php
namespace Shikoru\BulbapediaSetScraper\Objects;
class Common
{
	public $name, $number, $bulbapediaLink;

	public function setName(String $name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setNumber(int $number)
	{
		$this->number = $number;
	}

	public function getNumber()
	{
		return $this->number;
	}

	public function setBulbapediaLink(string $link)
	{
		//Match for type soon
		$this->link = $link;
	}

	public function getBulbaqpediaLink()
	{
		return $this->link;
	}
}