<?php
$srcRoot = __DIR__."/src";
$buildRoot = __DIR__."/build";

$pharName = "phpca.phar";
  
$phar = new Phar($buildRoot . "/" . $pharName, 
                 FilesystemIterator::CURRENT_AS_FILEINFO |       
                 FilesystemIterator::KEY_AS_FILENAME, $pharName);

$files = [
	"bootstrap.php",
	"PhpCa/Application.php",
	"PhpCa/UI/AbsoluteLayout.php",
	"PhpCa/UI/Inflatable.php",
	"PhpCa/UI/Label.php",
	"PhpCa/UI/Layout.php",
	"PhpCa/UI/TextField.php",
	"PhpCa/UI/UI.php",
	"PhpCa/UI/View.php"
];

foreach($files as $f) {
	$phar[$f] = file_get_contents($srcRoot . "/" . $f);
}

$phar->setStub($phar->createDefaultStub("bootstrap.php"));
 

