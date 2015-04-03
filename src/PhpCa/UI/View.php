<?php

namespace Faijdherbe\PhpCa\UI;

abstract class View 
{
	private $name = null;
	private $subViews = [];

	public function getName() {
		return $this->name;
	}

	protected function viewNamed($name) {
		if( $this->getName() == $name ) {
			return $this;
		}
		
		foreach($this->subViews as $view) {
			if(null != ($found = $view->viewNamed($name))) {
				return $found;
			}
		}

		return null;
	}

	public function registerNamespace($namespace, $prefix) {
	}

	public function registerAttribute($name, $value) {
	}

	abstract public function draw($x, $y, $w, $h);
}
