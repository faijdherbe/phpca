<?php

namespace Faijdherbe\PhpCa;

use Faijdherbe\PhpCa\UI\UI;
use Faijdherbe\PhpCa\UI\Inflatable;
use Faijdherbe\PhpCa\UI\Input;

abstract class Application extends Inflatable
{

	private static $currentApplication = null;

	private $shouldExitWithCode = false;
	private $firstResponder = null;

	private $lastCharacter = null;

	public static function current() {
		return self::$currentApplication;
	}

	final public function __construct(array $info) {
		self::$currentApplication = $this;

		register_shutdown_function(function() {
				system('stty sane');
				//UI::clear();
			});

		$this->applicationDidFinishLaunching($info);

		$this->run();
		
	}
	/*
	public function setRootView(View $rootView) {
		$this->rootView = $rootView;
	}

	public function getRootView() {
		return $this->rootView;
	}
	*/

	public function getFirstResponder() {
		return $this->firstResponder;
	}
	public function setFirstResponder(\Faijdherbe\PhpCa\UI\View $responder) {
		$this->firstResponder = $responder;
	}

	public function stopApplication($exitCode = 0) {
		$this->shouldExitWithCode = $exitCode;
	}

	public function getLastKey() {
		return $this->lastCharacter;
	}

	private function run() {
		$this->render();

		while(false === $this->shouldExitWithCode) {
			$this->readInput();
			$this->render();
		}

		exit($this->shouldExitWithCode);
	}

	private function readInput() {

		$c = Input::readKey();

		$this->lastCharacter = ord($c);

		switch(ord($c)) {
		case Input::KEY_CTRL_C: //ctrl-c
			$this->stopApplication();
			break;
		case Input::KEY_TAB: {
			if($r = $this->getNextResponder()) {
				$r->becomeFirstResponder();
			}
		}; break; 
		default:
			$this->handleInput($c);
			break;
		}
		
	}

	protected function handleInput($char) {
		if(null == $this->getFirstResponder() 
		   || false == $this->getFirstResponder()->handleInput($char)) {

			if(null !== $this->getRootView()) {
				$this->getRootView()->handleInput($char);
			}
		}
	}

	private function getNextResponder() {
		if( !$this->rootView ) {
			return null;
		}
		
		return $this->rootView->getNextResponder();
	}
	

	private function render() {
		UI::clear();
		$this->draw(0,0,24,80);

	}

	abstract protected function applicationDidFinishLaunching(array $info);

	public function draw($x, $y, $w, $h) {
		$this->getRootView()
			 ->draw($x, $y, $w, $h);
	}
}

