<?php

namespace Faijdherbe\PhpCa\UI;

abstract class UI 
{
	public static function clear($resetCursor = true) {
		echo static::ansi("2J");
		static::moveTo(0,0);
	}

	public static function moveTo($x, $y) {
		echo static::ansi(sprintf(
			"%d;%df",
			$y,
			$x
		));
	}

	private static function ansi($command) {
		return sprintf("\033[%s", $command);
	}
}
