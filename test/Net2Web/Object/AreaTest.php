<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * @covers \Gyron\Net2Web\Object\Area
 */
final class AreaTest extends TestCase {

	public function testEmptyXml(): void {
		//$this->expectException(InvalidArgumentException::class);
		new \Gyron\Net2Web\Object\Area( new SimpleXMLElement( '' ) );
	}
}

