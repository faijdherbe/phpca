<?php

namespace Faijdherbe\PhpCa\UI;

abstract class Input {

	const KEY_ARROW_UP = 65;
	const KEY_ARROW_DOWN = 66;
	const KEY_ARRAY_RIGHT = 67;
	const KEY_ARRAW_LEFT = 68;

	const KEY_ESC = 27;
	const KEY_ENTER = 10;
	const KEY_SPACE = 32;

	const KEY_CTRL_C = 3;
	const KEY_TAB = 9;


	public final static function readKey() {
		system("stty -echo -icanon intr undef");

		$c = fread(STDIN, 1);

		return $c;
	}
}
