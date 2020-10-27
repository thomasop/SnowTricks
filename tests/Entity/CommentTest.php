<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use PHPUnit\Framework\TestCase;


class CommentTest extends TestCase
{
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