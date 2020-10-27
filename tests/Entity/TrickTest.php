<?php

namespace App\Tests\Entity;

use App\Entity\Trick;
use PHPUnit\Framework\TestCase;


class TrickTest extends TestCase
{
	public function testName()
	{
		$trick = new Trick();
		$name = "Test nom";

		$trick->setName($name);
		$this->assertEquals("Test nom", $trick->getName());
	}

	public function testDescription()
	{
		$trick = new Trick();
		$description = "Test description";

		$trick->setDescription($description);
		$this->assertEquals("Test description", $trick->getDescription());
	}

	public function testPicture()
	{
		$trick = new Trick();
		$picture = "Test image";

		$trick->setPicture($picture);
		$this->assertEquals("Test image", $trick->getPicture());
	}
}