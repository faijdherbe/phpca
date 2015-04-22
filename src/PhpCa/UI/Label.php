<?php

namespace Faijdherbe\PhpCa\UI;

class Label extends View {

	private $text = null;

	public function getText() {
		return $this->text;
	}

	public function setText($text) {
		$this->text = $text;
	}

	public function draw($x, $y, $w, $h) {
		UI::moveTo($x, $y);
		printf('%s', $this->getText());
	}
}
