<?php

namespace App\Tests\Entity;

use App\Entity\Trick;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class TrickTest extends KernelTestCase
{
	public function testNameTrick()
	{
		$name = "okfl";
		$trick = (new Trick())
		->setName($name)
		->setDescription($name);
		self::bootKernel();
		$error = self::$container->get('validator')->validate($trick);
		$this->assertCount(0, $error);
	}

	public function testDescription()
	{
		$trick = new Trick();
		$description = "Test description";

		$trick->setName($description);
		$this->assertEquals("Test description", $trick->getName());
	}

	public function testPicture()
	{
		$trick = new Trick();
		$picture = "Test image";

		$trick->setPicture($picture);
		$this->assertEquals("Test image", $trick->getPicture());
	}
}