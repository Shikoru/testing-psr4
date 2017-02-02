<?php
require_once("./vendor/autoload.php");

use Shikoru\BulbapediaSetScraper\BulbapediaSetScraper;
use Shikoru\BulbapediaSetScraper\Objects;

//use emanueleminotto\simple-html-dom\simple_html_dom;
$testUrls = [];
$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Roaring_Skies_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Evolutions_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Steam_Siege_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/BREAKpoint_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/BREAKthrough_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Primal_Clash_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Furious_Fists_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/XY_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Plasma_Storm_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Dark_Explorers_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Black_%26_White_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Undaunted_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Platinum:_Arceus_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Base_Set_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Base_Set_2_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/Legendary_Collection_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/EX_Ruby_%26_Sapphire_(TCG)";
//$testUrls[] = "http://bulbapedia.bulbagarden.net/wiki/EX_Dragon_(TCG)";



foreach($testUrls as $url)
{
	$cardsArray = [];
	//$set = new Objects\Set();

	$bulba = new BulbapediaSetScraper($url);
	//	$bulba->loadUrl($url);
	echo "<pre>";
	var_dump($bulba->parseSet());
	echo "</pre>";
	echo "$url:<br>";

	//foreach($bulba->parseAdditionalCards() as $card)
//	{
//		//Only add what would be new from the card
//		if(!array_key_exists($card['number'], $cardsArray) || !is_object($cardsArray[$card['number']]))
//			$cardsArray[$card['number']] = new Objects\Card;
//		$cardsArray[$card['number']]->hasPromo = true;
//		$cardsArray[$card['number']]->finishes[] = $card['finish'];
//		$cardsArray[$card['number']]->promoText = $card['promoText'];
//		$cardsArray[$card['number']]->promoType = $card['promoRarity'];
//	}


//	foreach($bulba->parseCards() as $card)
//	{
//		echo "Card: {$card['number']}<br>";
//		//Only add what would be new from the card
//		if(!array_key_exists($card['number'], $cardsArray) || !is_object($cardsArray[$card['number']]))
//			$cardsArray[$card['number']] = new Objects\Card;
//		$cardsArray[$card['number']]->name = $card['name'];
//		$cardsArray[$card['number']]->number = $card['number'];
//		$cardsArray[$card['number']]->url = $card['url'];
//		$cardsArray[$card['number']]->type = $card['type'];
//		$cardsArray[$card['number']]->rarity = $card['rarity'];
//		$cardsArray[$card['number']]->finishes = $card['finishes'];
//		$cardsArray[$card['number']]->evolutionStage = $card['evolutionStage'];

//		//$cardsArray[$card['number']] = array_merge($bulba->parseDetailedCardInfo($card['url']));

//		//Set the actial total acording to the number of the last card
//		$set->actualTotal = $card['number'];

//	}
	echo "<pre>";
	echo "</pre>";

}


?>
