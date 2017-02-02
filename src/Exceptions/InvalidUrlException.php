<?php
namespace Shikoru\BulbapediaSetScraper\Exceptions;
	class InvalidUrlException extends \Exception
	{
		public function __construct($message = null, $code = 0, Exception $previous = null)
		{
			parent::__construct($message, $code, $previous);
		}
	}
?>
