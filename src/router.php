<?php

namespace gcgov\framework\services\documentation;

use gcgov\framework\config;
use gcgov\framework\models\route;

class router
	implements
	\gcgov\framework\interfaces\router {

	public function getRoutes(): array {
		return [
			new route( 'GET', config::getEnvironmentConfig()->getBasePath() . '/documentation.yaml', '\gcgov\framework\services\documentation\controllers\documentation', 'yaml', false )
		];
	}


	public function authentication( \gcgov\framework\models\routeHandler $routeHandler ): bool {
		return true;
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
