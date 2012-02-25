<?php
require_once 'silex.phar';

spl_autoload_register( function($class) {
	$file = strtr($class, '\\', '/').'.php';
	if (file_exists(__DIR__.'/../src/'.$file)) {
		require __DIR__.'/../src/'.$file;
	} else {
		$file = substr($file, strlen('Symfony\Component\\'));
		require __DIR__.'/../vendor/'.$file;
	}
});