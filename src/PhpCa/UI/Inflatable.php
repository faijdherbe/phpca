<?php

namespace Faijdherbe\PhpCa\UI;

use \XMLReader as XMLReader;

abstract class Inflatable extends View 
{

	private $rootView = null;

	protected function inflate($filename) {
		$this->rootView = new AbsoluteLayout();

		if(false == file_exists($filename)) {
			throw new \UnexpectedValueException();
		}
		
		$xml = new XMLReader();
		$xml->open($filename);

		$xml->read();

		$subTree = $this->inflateView($xml, null);
		
		$xml->close();
	}

	private function inflateView(XMLReader $xml, View $view = null) {

		if(XMLReader::ELEMENT !== $xml->nodeType) {
			throw new \RuntimeException();
		}

		error_log($xml->name);

		$view = new AbsoluteLayout();

		if(true == $xml->moveToFirstAttribute()) {
	   		do {
				if('xmlns' == $xml->prefix) {
					$view->registerNamespace(
						$xml->value,
						$xml->localName
					);
				} else {
					if(empty($xml->prefix)) {
						$methodName = sprintf(
							'set%s', ucfirst($xml->localName)
						);
						$view->$methodName($xml->value);
					} else {
						$view->registerAttribute($xml->name, $xml->value);
					}
				}
				error_log(sprintf(
					'- (%s) %s: %s',
					$xml->prefix,
					$xml->localName,
					$xml->value
				));
		    } while(true == $xml->moveToNextAttribute());
			
			$xml->moveToElement();
		}

		if(false == $xml->isEmptyElement) {
			do { 
				$xml->read();

				if(XMLReader::ELEMENT == $xml->nodeType) {
					$view->subViews[] = $this->inflateView($xml, null);
				} elseif(XMLReader::END_ELEMENT == $xml->nodeType) {
					break;
				}
		   
			} while(true);
		}

		return $view;
	}
	
	protected function rootView() {
		return $this->rootView;
	}

}
