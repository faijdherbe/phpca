<?php

namespace Faijdherbe\PhpCa\UI;

class AbsoluteLayout extends Layout
{
	protected static $namespaceUri = 'http://faijdherbe.net/phpca/ui/absolutelayout';
 
	public function draw($x, $y, $w, $h) {
		foreach($this->getSubviews() as $view) {
			$vx = $view->getAttribute('x', 0, $this);
			$vy = $view->getAttribute('y', 0, $this);
			$vw = $view->getAttribute('width', 0, $this);
			$vh = $view->getAttribute('height', 0, $this);

			$view->draw(
				$vx + $x,
				$vy + $y,
				$vw,
				$vh
			);
		}
	}
}
