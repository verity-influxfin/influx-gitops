<?php

class Regular_expression
{
	const LINE_BREAK = "(\r\n|\r|\n)*";
	const SPACES = "[\s\r\n]+";

	public function findPatternInBetween(string $text, string $start, string $end)
	{
		$lineBreaks = self::LINE_BREAK;
		preg_match("/(?<={$start}){$lineBreaks}.*{$lineBreaks}(?={$end})/s", $text, $match);
		return $match;
	}

	public function replaceSpacesToSpace(string $text)
	{
		$spaces = self::SPACES;
		$text = trim($text);
		return preg_replace("/$spaces/", " ", $text);
	}
}
