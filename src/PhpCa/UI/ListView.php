<?php

namespace Faijdherbe\PhpCa\UI;

use \Faijdherbe\PhpCa\UI\Input;

class ListView extends \Faijdherbe\PhpCa\UI\View
{
	private $title;
	private $border;
	private $background;
	private $foreground;
	private $selection;
	private $onDidSelectItem;


	private $options = [
		'Foo',
		'Bar',
		'Baz'
	];
	private $currentIndex = 0;

	public function setTitle($title) {
		$this->title = $title;
	}
	public function getTitle() {
		return $this->title;
	}

	public function setBorder($border) {
		$this->border = $border;
	}
	public function getBorder() {
		return $this->border;
	}

	public function setBackground($background) {
		$this->background = $background;
	}
	public function getBackground() {
		return $this->background;
	}

	public function setForeground($foreground) {
		$this->foreground = $foreground;
	}
	public function getForeground() {
		return $this->foreground;
	}

	public function setSelectionColor($selection) {
		$this->selectionColor = $selection;
	}
	public function getSelection() {
		return $this->selectionColor;
	}

	public function getOptions() {
		return $this->options;
	}

	public function setOptions(array $options) {
		$this->options = $options;
	}

	protected function didSelectItemAtIndex($index) {
		
	}

	public function canBecomeFirstResponder(){
		return true;
	}

	public function handleInput($input) {

		switch(ord($input)) {
		case Input::KEY_ARROW_UP:
			$this->currentIndex =  (count($this->options) + --$this->currentIndex) % count($this->options);
			break;

		case Input::KEY_ARROW_DOWN:
			$this->currentIndex = (count($this->options) + ++$this->currentIndex) % count($this->options);
			break;

		case Input::KEY_ENTER:
			$c = $this->onDidSelectItem;
			
			$c($this->currentIndex);
			break;

		default:
			return false;
			break;
		}

		return true;
	}

	public function setOnDidSelectItem(callable $c) {
		$this->onDidSelectItem = $c;
	}

	public function draw($x, $y, $w, $h) {
		UI::MoveTo($x, $y);
		print($this->getTitle());

		UI::MoveTo($x, $y+1);
		print(str_repeat('=', $w));

		foreach($this->options as $index=>$option) {
			UI::MoveTo($x, $y + $index + 2);
			$prefix = '  ';
			if($index == $this->currentIndex) {
				$prefix = '> ';
			}
			print($prefix . $option);
		}
	}

}
