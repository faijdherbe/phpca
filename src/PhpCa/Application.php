<?php

namespace Faijdherbe\PhpCa;

use Faijdherbe\PhpCa\UI\UI;
use Faijdherbe\PhpCa\UI\Inflatable;

abstract class Application extends Inflatable
{

	private static $currentApplication = null;

	private $shouldExitWithCode = false;
	private $rootView = null;
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

	public function setRootView(View $rootView) {
		$this->rootView = $rootView;
	}

	public function getRootView() {
		return $this->rootView;
	}
	
	public function getFirstResponder() {
		return $this->firstResponder;
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
		system("stty -echo -icanon intr undef");
		$c = fread(STDIN, 1);

		$this->lastCharacter = ord($c);

		switch(ord($c)) {
		case 3: //ctrl-c
			$this->stopApplication();
			break;
		case 9: {
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
		$this->rootView()
			 ->draw($x, $y, $w, $h);
	}
}
