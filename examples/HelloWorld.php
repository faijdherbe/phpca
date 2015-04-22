#!/usr/bin/php
<?php

//require (__DIR__ . '/../build/phpca.phar');
require(__DIR__ . '/../src/bootstrap.php');

use \Faijdherbe\PhpCa\Application;

class App extends Application
{
	private $window = null;
	
	protected function applicationDidFinishLaunching(array $info) {
		$this->inflate('layout/helloworld.xml');
	}

	private function exitButtonPressed(Button $sender) {
		$this->exit(0);
	}
}

\Faijdherbe\PhpCa\UI\Inflatable::registerNamespace(
	'\\',
	'http://test'
);

class KeyMonitor extends \Faijdherbe\PhpCa\UI\Label {
	public function draw($x, $y, $w, $h) {
		$this->setText(
			Application::current()->getLastKey()
		);
		parent::draw($x, $y, $w, $h);
	}
}

class Button extends \Faijdherbe\PhpCa\UI\Label {
	public function setOnClick($method) {
	}
	
	public function acceptFocus(){
		return true;
	}
	
	public function setFocus($focus) {

	}
}

new App([]);
