<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;



class CommentTest extends KernelTestCase
{

	public function testCommentOk()
	{
		$name = "okfl";
		$user = (new Comment())
		->setName($name)
		->setContent("dfdgggddggdg");
		self::bootKernel();
		$error = self::$container->get('validator')->validate($user);
		$this->assertCount(0, $error);
	}

	public function testPseudo()
	{
		$comment = new Comment();
		$name = "Test name";

		$comment->setName($name);
		$this->assertEquals("Test name", $comment->getName());
	}

	public function testContent()
	{
		$comment = new Comment();
		$content = "Test content";

		$comment->setContent($content);
		$this->assertEquals("Test content", $comment->getContent());
	}

	public function testDate()
	{
		$comment = new Comment();
		$date = new \DateTime();
		$createdAt = $date;

		$comment->setDate($createdAt);
		$this->assertEquals($date, $comment->getDate());
	}
}