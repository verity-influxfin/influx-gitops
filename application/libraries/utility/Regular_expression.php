<?php

class Regular_expression
{
	const LINE_BREAK = "(\r\n|\r|\n)*";
	const SPACES = "[\s\r\n]+";
	const DIGITS = '[0-9]+';

	public function findPatten(string $text , string $patten)
    {
        preg_match("/$patten/s", $text, $match);
        return $match;
    }

	public function findPatternInBetween(string $text, string $start, string $end)
	{
		$lineBreaks = self::LINE_BREAK;
		preg_match("/(?<={$start}){$lineBreaks}.*{$lineBreaks}(?={$end})/s", $text, $match);
		return $match;
	}

	public function findNonGreedyPatternInBetween(string $text, string $start, string $end)
	{
		$lineBreaks = self::LINE_BREAK;
		preg_match("/(?<={$start}){$lineBreaks}.*?{$lineBreaks}(?={$end})/s", $text, $match);
		return $match;
	}

	public function replaceSpacesToSpace(string $text)
	{
		$spaces = self::SPACES;
		$text = trim($text);
		return preg_replace("/$spaces/", " ", $text);
	}

	public function containDigit(string $text)
	{
		$digits = self::DIGITS;
		preg_match("/{$digits}/", $text, $match);
		if ($match) {
			return true;
		}
		return false;
	}
}
