<?php
namespace Shikoru\BulbapediaSetScraper;

use Shikoru\BulbapediaSetScraper\Exceptions;

class BulbapediaSetScraper {

	protected $set;
	protected $setUrl;
	protected $cardsArray;
	protected $return;
	protected $setHtml;
	protected $pageType;

	public function __construct($url)
	{
		$this->setUrl = $url;
		$scrape1 = new Tools\Scraper();
		$this->setHtml = $scrape1->loadUrl($url);
	}


	public function parseSet()
	{
		$this->set = new Objects\Set();
		$this->set->name = $this->parseSetName();
		$this->set->listedTotal = $this->parseSetListedTotal();
		$this->set->number = $this->parseSetNumber();
		$this->set->releaseDate = $this->parseReleaseDate();
		$this->set->bulbapediaLink = $this->setUrl;
		$this->set->cards = [];
		$this->parseAdditionalCards();
		$this->parseCards();
		foreach($this->set->cards as $card)
		{
			$this->parseDetailedCardInfo($card);
		}

		return $this->set;
	}
	protected function parseSetName()
	{
		$search = [];
		$search[] = "/html/body/div[1]/div[1]/div/div[4]/div[1]/div[2]/div[4]/table[2]/tbody/tr[1]/td/table/tbody/tr/td[1]/big/big/b";
		foreach($search as $src)
		{
			$result = $this->setHtml->find($src, 0);
			if(is_object($result) && trim($result->innertext) != "")
				return $result->innertext;
		}
		return false;
	}

	protected function parseSetListedTotal()
	{
		$search = [];
		$search[] = "/html/body/div[1]/div[1]/div/div[4]/div[1]/div[2]/div[4]/table[2]/tbody/tr[2]/td/table[2]/tbody/tr[1]/td";
		foreach($search as $src)
		{
			$result = $this->setHtml->find($src, 0);
			if(is_object($result) && trim($result->innertext) != "")
			{
				preg_match("/English:\s?(\d+)/i", $result->innertext, $hld);
				//Fix for base set where english is not listed
				return array_key_exists(1, $hld) ? $hld[1] : $result->innertext;
			}
		}
		return false;
	}

	protected function parseSetNumber()
	{
		$search = [];
		$search[] = "//*[@id='mw-content-text']/table[2]/tbody/tr[2]/td/table[2]/tbody/tr[2]/td";
		foreach($search as $src)
		{
			$result = $this->setHtml->find($src, 0);
			if(is_object($result) && trim($result->innertext) != "")
			{
				preg_match("/English:\s?(\d+)/i", $result->innertext, $hld);
				//Fix for base set where english is not listed
				return array_key_exists(1, $hld) ? $hld[1] : $result->innertext;
			}
		}
		return false;
	}

	protected function parseReleaseDate()
	{
		$search = [];
		$search[] = "//*[@id='mw-content-text']/table[2]/tbody/tr[2]/td/table[2]/tbody/tr[3]/td";
		foreach($search as $src)
		{
			$result = $this->setHtml->find($src, 0);
			if(is_object($result) && trim($result->innertext) != "")
			{
				preg_match("/English:\s?([a-z]+\s?\d+,?\s?\d+)/i", $result->innertext, $hld);
				//Fix for base set where english is not listed
				return array_key_exists(1, $hld) ? $hld[1] : $result->innertext;
			}
		}
		return false;
	}

	protected function parseAdditionalCards()
	{
		$search = [];
		$search[] = "//*[@id='mw-content-text']/table[3]/tbody/tr[2]/td/table/tbody";
		foreach($search as $src)
		{
			$result = $this->setHtml->find($src, 0);
			if(is_object($result) && trim($result->innertext) != "")
			{
				$invRows = $result->find("tr");
				array_shift($invRows);
				array_pop($invRows);
				if(count($invRows) > 0)
				{
					foreach($invRows as $row)
					{
						$cardNumber = explode("/", $row->find("td[0]", 0)->plaintext)[0];


						if(!array_key_exists($cardNumber, $this->set->cards) || !is_object($this->set->cards[$cardNumber]))
							$this->set->cards[$cardNumber] = new Objects\Card;

						$this->set->cards[$cardNumber]->number = $cardNumber;
						$this->set->cards[$cardNumber]->name = $row->find("td[3]", 0)->plaintext;
						$this->set->cards[$cardNumber]->url = "http://bulbapedia.bulbagarden.net" . $row->find("td[3] a", 0)->href;

						$this->set->cards[$cardNumber]->type = $row->find("th[1] img", 0)->alt;
						$promoText = $row->find("td[5]", 0)->plaintext;
						//Finish checks
						if(strpos($promoText, "Cracked Ice"))
						{
							$this->set->cards[$cardNumber]->finishes[] = "Cracked Ice Holo";
						}
						elseif(strpos($promoText, "Non Holo"))
						{
							$this->set->cards[$cardNumber]->finishes[] = "Normal";
						}
						elseif(strpos($promoText, "Sheen Holo"))
						{
							$this->set->cards[$cardNumber]->finishes[] = "Sheen Holo";
						}
						elseif(strpos($promoText, "Cosmos Holo"))
						{
							$this->set->cards[$cardNumber]->finishes[] = "Cosmos Holo";
						}
						elseif(strpos($promoText, "Crosshatch Holo"))
						{
							$this->set->cards[$cardNumber]->finishes[] = "Crosshatch Holo";
						}
						elseif(strpos($promoText, "	Mirror Holo"))
						{
							$this->set->cards[$cardNumber]->finishes[] = "Mirror Holo";
						}
						else
						{
							$this->set->cards[$cardNumber]->finishes[] = "Normal";
						}

						//Rarity checks
						if(strpos($promoText, "Theme Deck exclusive"))
						{
							$this->set->cards[$cardNumber]->promoRarity = "Theme Deck Exclusive";
						}
						elseif(strpos($promoText, "Collector Chest exclusive"))
						{
							$this->set->cards[$cardNumber]->promoRarity = "Collector Chest Exclusive";
						}
						elseif(strpos($promoText, "blister") || strpos($promoText, "blisters"))
						{
							$this->set->cards[$cardNumber]->promoRarity = "Blister Pack";
						}
						elseif(strpos($promoText, "League Challenge promo"))
						{
							$this->set->cards[$cardNumber]->promoRarity = "League Challenge Promo";
						}
						elseif(strpos($promoText, "Season promo"))
						{
							$this->set->cards[$cardNumber]->promoRarity = "Season Promo";
						}
						elseif(strpos($promoText, "Regional Championships"))
						{
							$this->set->cards[$cardNumber]->promoRarity = "Regional Championships Promo";
						}
						//Almost Last
						elseif(strpos($promoText, "stamp promo"))
						{
							$this->set->cards[$cardNumber]->promoRarity = "Stamp Promo";
						}
						else
						{
							$this->set->cards[$cardNumber]->promoRarity = "Unknown Promo";
						}

						//To have the promo text if ever needed
						$this->set->cards[$cardNumber]->promoText = $promoText;
					}
				}
			}
		}
	}

	//Parse basic card info from the set page
	protected function parseCards()
	{
		$return = [];
		$search = [];
		$search[] = "//*[@id='mw-content-text']/table[4]/tbody/tr/td[1]/table/tbody/tr[2]/td/table";
		foreach($search as $src)
		{
			$result = $this->setHtml->find($src, 0);
			if(is_object($result) && trim($result->innertext) != "")
			{
				//If not an empty result
				//Find all table rows and remove the emprt first and last row
				$invRows = $result->find("tr");
				array_shift($invRows);
				array_pop($invRows);
				if(count($invRows) > 0)
				{
					//$localRet = [];
					foreach($invRows as $row)
					{
						$localRet = [];
						$cardNumber = explode("/", $row->find("td[0]", 0)->plaintext)[0];

						if(!array_key_exists($cardNumber, $this->set->cards) || !is_object($this->set->cards[$cardNumber]))
							$this->set->cards[$cardNumber] = new Objects\Card;


						$this->set->cards[$cardNumber]->number = $cardNumber;
						$this->set->cards[$cardNumber]->name = $this->processCardName($row->find("td[3]", 0));
						$this->set->cards[$cardNumber]->url = "http://bulbapedia.bulbagarden.net" . $row->find("td[3] a", 0)->href;
						if(preg_match("/File:/i", $this->set->cards[$cardNumber]->url))
						{
							$this->set->cards[$cardNumber]->url = "http://bulbapedia.bulbagarden.net".$row->find('td[3] a', 1)->href;
						}

						$cardTypeRaw = $row->find('th', 0);
						$cardTypeRawImageObject = $cardTypeRaw->find('img', 0);

						//Card Type
						if(is_object($cardTypeRawImageObject))
						{
							switch(strtolower($cardTypeRawImageObject->alt))
							{
								case "grass":
								case "fire":
								case "water":
								case "lightning":
								case "psychic":
								case "fighting":
								case "darkness":
								case "metal":
								case "fairy":
								case "colorless":
								case "dragon":
									if(trim($cardTypeRaw->plaintext) == "")
									{
										$type = "pokemon";
									}
									else
									{
										//Is possibly an energy
										$type = "energy";
									}
									break;

								default:
									$type = "undefined";

									break;
							}
						}
						else
						{
							switch(strtolower(trim($cardTypeRaw->plaintext)))
							{
								case "i":
									$type = "item";
									break;

								case "st":
									$type = "stadium";
									break;

								case "su":
									$type = "supporter";
									break;

								default:
									$type = "undefined";
									break;
							}
						}
						if(is_null($type) || $type == "")
							$type = "unknown";

						$this->set->cards[$cardNumber]->type = $type;

						$this->set->cards[$cardNumber]->rarity = $row->find("td[4] img", 0)->alt;

						$this->set->cards[$cardNumber]->finishes = array_merge($this->set->cards[$cardNumber]->finishes, $this->getFinishesFromRarity($this->set->cards[$cardNumber]->rarity));
					}
				}
			}
		}
		return $return;
	}

	public function parseDetailedCardInfo(Objects\Card &$card)
	{
		$cardHtml = new Tools\Scraper($card->url);
		$cardHtml = $cardHtml->loadUrl($card->url);
		$this->deleteWarningsFromCardHtml($cardHtml);
		switch($card->type)
		{
			case "pokemon":

				$evolutionStageSearch = [];
				//Works for M-EX
				$evolutionStageSearch[] = "//*[@id='mw-content-text']/table[1]/tbody/tr[3]/td/table[1]/tbody/tr[1]/td/table/tbody/tr/td[2]/a";
				$evolutionStageSearch[] = "//*[@id='mw-content-text']/table[1]/tbody/tr[3]/td/table[1]/tbody/tr[1]/td/a";
				foreach($evolutionStageSearch as $src)
				{
					$result = $cardHtml->find($src, 0);
					if(is_object($result) && trim($result->plaintext) != "")
					{
						$card->evolutionStage = $result->plaintext;
						break;
					}
				}

				$card->hp = $cardHtml->find("//*[@id='mw-content-text']/table[1]/tbody/tr[3]/td/table[1]/tbody/tr[4]/td", 0)->plaintext;
				$card->weakness = $this->processCardWeakness($cardHtml);

				break;
		}
	}

	protected function getFinishesFromRarity($rarity)
	{
		$finishes = [];
		switch($rarity)
		{
			case "Common":
			case "Uncommon":
			case "Rare":
				$finishes[] = "normal";
				$finishes[] = "reverse_holo";

				break;

			case "Rare Holo":
				$finishes[] = "holo";
				$finishes[] = "reverse_holo";
				break;

			case "Rare Ultra":
			case "SuperRare Holo":
				$finishes[] = "full_art";
				break;

			case "Rare Secret":
				$finishes[] = "gold";
				break;

			case "Rare Holo ex":
				$finishes[] = "full_art";
				//Not sure of the other finish
				$finishes[] = "normal";


				//Technicaly a holoish card but treating as normal for now
			case "Mega":
			case "BREAK":
			case "EX":
				//Unknown at the moment
			case "Rare Ace":
			default:
				$finishes[] = "normal";
				break;
		}
		return $finishes;
	}


	protected function deleteWarningsFromCardHtml(&$cardHtml)
	{
		$tbl = $cardHtml->find("//*[@id='mw-content-text']/table[1]",0);
		if(is_object($tbl))
		{
			if(strpos($tbl->plaintext, "The picture used in this article is unsatisfactory"))
			{
				$cardHtml->find("//*[@id='mw-content-text']/table[1]",0)->outtertext = "";
			}
		}
	}

	protected function processCardName($lineObject)
	{
		$return = [];
		$plainName = trim($lineObject->plaintext);
		if(preg_match("/EX/", $lineObject))
			//            $cardData['name'] .= " Ex";
			$addEx = true;
		else
			$addEx = false;

		if(preg_match("/M_/", $lineObject))
			//            $cardData['name'] = "M " . $cardData['name'];
			$addMega = true;
		else
			$addMega = false;

		if(strpos($plainName, "["))
		{
			//Prepare the string a little
			preg_match_all("/([\w\s\d]+)[\s?]+\[([\w\s\d]+)\]/i", preg_replace("/\r|\n/", "", $plainName), $namesArray, PREG_SET_ORDER);
			foreach($namesArray as $key => $value)
			{

				$returnName = $value[1];
				if($addEx)
					$returnName .= " Ex";
				if($addMega)
					$returnName = "M " . $returnName;

				$returnName .=  " (" . $value[2] . ")";

				$return[] = $returnName;
			}
		}
		else
		{
			if($addEx)
			{
				$plainName .= " Ex";
			}
			if($addMega){
				$plainName = "M " . $plainName;
			}
			$return = $plainName;
		}
		return $return;
	}

	protected function processCardWeakness($cardHtml)
	{
		$placeholder = $cardHtml->find('//*[@id="mw-content-text"]/table[1]/tbody/tr[3]/td/table[1]/tbody/tr[5]/td/table/tbody/tr/th[1]', 0);
		if(preg_match("/none/i", $placeholder->plaintext))
		{
			return "none";
		}
		else
		{
			$find = array("weakness", "\r", "\n");
			$replace = array("");
			$weaknessModifier = str_replace($find, $replace, trim($placeholder->plaintext));
			$weaknessType = $placeholder->find("img", 0)->alt;
			return $weaknessType . " " . $weaknessModifier;
		}
	}
}