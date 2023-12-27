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
		$scanDirectories = $this->getScanDirectories();
		$excludeFilesDirectories = $this->getExcludeDirectoriesFiles();
		$files = \OpenApi\Util::finder($scanDirectories, $excludeFilesDirectories, '*.php');
		$openapi           = \OpenApi\Generator::scan( $files );
		header( 'Content-Type: text/x-yaml' );
		echo $openapi->toYaml();
		die();
	}


	/**
	 * @return string[]
	 */
	private function getScanDirectories(): array {
		$directoriesToScan = [
			config::getAppDir(),
			config::getRootDir() . '/vendor/gcgov/framework/src/controllers',
			config::getRootDir() . '/vendor/gcgov/framework/src/exceptions',
			config::getRootDir() . '/vendor/gcgov/framework/src/models',
			config::getRootDir() . '/vendor/gcgov/framework/src/services'
		];

		foreach( $directoriesToScan as $i=>$directory ) {
			if(!file_exists($directory)) {
				unset($directoriesToScan[$i]);
			}
		}

		return array_values( $directoriesToScan );
	}


	/**
	 * @return string[]
	 */
	private function getExcludeDirectoriesFiles(): array {
		$exclusions = [
			config::getRootDir() . '/vendor',
		];

		foreach( $exclusions as $i=>$exclude ) {
			if(!file_exists($exclude)) {
				unset($exclusions[$i]);
			}
		}

		if( class_exists('\app\models\user') ) {
			$exclusions[] = config::getRootDir() . '/vendor/gcgov/framework/src/services/mongodb/models/auth/user.php';
		}

		if( class_exists('\app\models\authUser') ) {
			$exclusions[] = config::getRootDir() . '/vendor/gcgov/framework/src/models/authUser.php';
		}

		return array_values( $exclusions );
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
