<?php

namespace App\Tests\Entity;

use App\Entity\Image;
use PHPUnit\Framework\TestCase;


class ImageTest extends TestCase
{
	public function testNameImage()
	{
		$image = new Image();
		$name = "Test image";

		$image->setName($name);
		$this->assertEquals("Test image", $image->getName());
	}
}