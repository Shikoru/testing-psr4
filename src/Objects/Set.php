<?php
namespace Shikoru\BulbapediaSetScraper\Objects;
class Set extends Common
{
	public $releaseDate, $listedTotal, $actualTotal, $cards;

	public function setReleaseDate(Datetime $releaseDate)
	{
		$this->releaseDate = $releaseDate;
	}

	public function getReleaseDate()
	{
		return $this->releaseDate;
	}

	public function setListedTotal(int $listedTotal)
	{
		$this->listedTotal = $listedTotal;
	}

	public function getListedTotal()
	{
		return $this->listedTotal;
	}

	public function setActualTotal(int $actualTotal)
	{
		$this->actualTotal = $actualTotal;
	}

	public function getActualTotal()
	{
		return $this->actualTotal;
	}
}