#!/usr/bin/php
<?php

require __DIR__ . '/../../src/bootstrap.php';

use \Faijdherbe\PhpCa\Application;


\Faijdherbe\PhpCa\UI\Inflatable::registerNamespace(
	'\\',
	'http://rpg.examples.phpca.faijdherbe.net'
);

class Game extends Application 
{
	private $window;
	
	protected function applicationDidFinishLaunching(array $info) {
		$this->inflate('layout/main.xml');

		$menuView = $this->getRootView()
			->findView('menu_view');

		$menuView->becomeFirstResponder();
		$menuView->setOnDidSelectItem(function($index) use ($menuView) {
					 if(2 == $index) {
						 exit(0);
					 } else {
						 $menuView->setOptions(
							 array_merge($menuView->getOptions(), ['XX'])
						 );
					 }
			 });
	}

}

new Game([]);

