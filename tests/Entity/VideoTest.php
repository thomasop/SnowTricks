<?php

namespace App\Tests\Entity;

use App\Entity\Video;
use PHPUnit\Framework\TestCase;


class VideoTest extends TestCase
{
	public function testUrl()
	{
		$video = new Video();
		$url = "Test video";

		$video->setUrl($url);
		$this->assertEquals("Test video", $video->getUrl());
	}
}