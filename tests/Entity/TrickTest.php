<?php

namespace App\Tests\Entity;

use App\Entity\Trick;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class TrickTest extends KernelTestCase
{
	public function testName()
	{
		$trick = (new Trick())
		//$name = "Test nom";
		->setName("Test nom")
		->setDescription("Test description")
		->setPicture("Test image");
		self::bootKernel();
		$error = self::$container->get('validator')->validate($trick);
		$this->assertCount(0, $error);
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