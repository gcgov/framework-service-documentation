<?php

namespace gcgov\framework\services\documentation\controllers;

use gcgov\framework\config;
use gcgov\framework\interfaces\controller;
use gcgov\framework\models\controllerDataResponse;
use JetBrains\PhpStorm\NoReturn;

class documentation implements controller {

	public function __construct() {

	}


	#[NoReturn]
	public function yaml(): void {
		$openapi = \OpenApi\Generator::scan( [ config::getRootDir() . '/app', config::getRootDir() . '/vendor/gcgov/framework/src/controllers', config::getRootDir() . '/vendor/gcgov/framework/src/exceptions', config::getRootDir() . '/vendor/gcgov/framework/src/models', config::getRootDir() . '/vendor/gcgov/framework/src/services' ], [ 'exclude' => [ 'vendor' ] ] );
		header( 'Content-Type: text/x-yaml' );
		echo $openapi->toYaml();
		die();
	}


	public function routes(): controllerDataResponse {
		$routes = [];
		return new controllerDataResponse( $routes );
	}


	/**
	 * Processed after lifecycle is complete with this instance
	 */
	public static function _after(): void {
	}


	/**
	 * Processed prior to __constructor() being called
	 */
	public static function _before(): void {
	}

}
