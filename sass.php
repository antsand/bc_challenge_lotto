<?php
	/* This file converts scss file to css
	 * We use scssphp which is a php library 
	 * to interface with sass.
	 *
	 * To execute this file type on the command line:
	 * php sass.php 
	 */
	$sass = new Sass();
	try {
		$sass->setIncludePath(getcwd() . '/node_modules/:' . getcwd() . '/app/sass/');
		$css_file = file_get_contents(getcwd() . '/app/sass/main.scss');
		$sass->setStyle(Sass::STYLE_COMPRESSED);
		$css = $sass->compile($css_file);
		file_put_contents(getcwd() . '/public/css/main.css', $css);
	    } catch (SassException $e) {
		//print_r($e);
		$error = new stdClass();
		$error->error = $e->getMessage();
		print_r($error);
		return;
	}



?>
