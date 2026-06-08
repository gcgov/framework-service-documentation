<?php

declare(strict_types=1);

namespace gcgov\framework\services\documentation\tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use gcgov\framework\services\documentation\router;
use gcgov\framework\models\environmentConfig;
use gcgov\framework\models\route;

#[CoversClass(router::class)]
final class RouterTest extends TestCase {

	protected function setUp(): void {
		$envConfig = new environmentConfig();
		$envConfig->basePath = 'api/v1';

		$prop = new \ReflectionProperty( \gcgov\framework\config::class, 'environmentConfig' );
		$prop->setValue( null, $envConfig );
	}

	public function testRouterImplementsFrameworkRouterInterface(): void {
		$this->assertContains(
			\gcgov\framework\interfaces\router::class,
			class_implements( router::class ) ?: []
		);
	}

	public function testGetRoutesReturnsSingleDocumentationYamlRoute(): void {
		$routes = ( new router() )->getRoutes();
		$this->assertCount( 1, $routes );
		$this->assertInstanceOf( route::class, $routes[0] );
	}

	public function testRouteIsGetMethodAtConfiguredBasePath(): void {
		$routes = ( new router() )->getRoutes();
		/** @var route $route */
		$route = $routes[0];

		$this->assertSame( 'GET', $route->httpMethod );
		$this->assertSame( '/api/v1/documentation.yaml', $route->route );
	}

	public function testRouteTargetsDocumentationControllerYamlMethod(): void {
		$routes = ( new router() )->getRoutes();
		/** @var route $route */
		$route = $routes[0];

		$this->assertSame( '\gcgov\framework\services\documentation\controllers\documentation', $route->class );
		$this->assertSame( 'yaml', $route->method );
	}

	public function testRouteIsUnauthenticated(): void {
		$routes = ( new router() )->getRoutes();
		/** @var route $route */
		$route = $routes[0];

		$this->assertFalse( $route->authentication );
	}

	public function testAuthenticationAlwaysReturnsTrue(): void {
		$routeHandler = $this->createStub( \gcgov\framework\models\routeHandler::class );
		$this->assertTrue( ( new router() )->authentication( $routeHandler ) );
	}

	public function testLifecycleHooksAreCallableAndReturnVoid(): void {
		router::_before();
		router::_after();

		$reflection = new \ReflectionClass( router::class );
		$this->assertSame( 'void', (string) $reflection->getMethod( '_before' )->getReturnType() );
		$this->assertSame( 'void', (string) $reflection->getMethod( '_after' )->getReturnType() );
	}

}
