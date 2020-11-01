<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;


class CategoryTest extends TestCase
{
	public function testTitle()
	{
		$category = new Category();
		$title = "Test category";

		$category->setTitle($title);
		$this->assertEquals("Test category", $category->getTitle());
	}
}