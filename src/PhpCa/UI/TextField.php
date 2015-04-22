<?php

namespace Faijdherbe\PhpCa\UI;

class TextField extends View
{
	private $prompt = '';
	private $text = '';
	private $placeholder = '';

	public function handleInput($char) {
		$ord = ord($char);

		error_log($ord);
		$this->text .= $char;

		return true;
	}

	public function setText($text) {
		$this->text = $text;
	}

	public function draw($x, $y, $w, $h) {
		UI::moveTo($x,$y);
		$text = $this->text;
		if(empty($text)) {
			$text = $this->placeholder;
		}
		$len = max(0, $w - strlen($this->prompt));
		$text = substr($text, -$len);
		print($this->prompt . $text);
	}

}
