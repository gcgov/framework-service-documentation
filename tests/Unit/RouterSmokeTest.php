<?php

declare(strict_types=1);

namespace gcgov\framework\services\documentation\tests\Unit;

use PHPUnit\Framework\TestCase;
use gcgov\framework\services\documentation\router;

final class RouterSmokeTest extends TestCase {

	public function testRouterImplementsFrameworkRouterInterface(): void {
		$this->assertContains(
			\gcgov\framework\interfaces\router::class,
			class_implements( router::class ) ?: []
		);
	}

	public function testAuthenticationIsPublic(): void {
		$routeHandler = $this->createStub( \gcgov\framework\models\routeHandler::class );
		$this->assertTrue( ( new router() )->authentication( $routeHandler ) );
	}

}
