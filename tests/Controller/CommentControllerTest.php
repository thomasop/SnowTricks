<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;
use PHPUnit\Framework\TestCase;

class CommentControllerTest extends WebTestCase
{
    private $client = null;
  
  public function testCommentIsUp()
  {
    $this->client = static::createClient();
    $this->client->request('GET', '/comment/720/1');
    
    static::assertEquals(
      Response::HTTP_OK,
      $this->client->getResponse()->getStatusCode()
    );
  }

  public function testAddCommentIsUp()
  {
    $this->client = static::createClient();
    $this->client->request('GET', '/comment/720/1');
    
    static::assertEquals(
      Response::HTTP_OK,
      $this->client->getResponse()->getStatusCode()
    );
  }

  public function testDeleteCommentIsUp()
  {
    $this->client = static::createClient();
    $this->client->request('GET', '/delete_comment/2/720');
    
    static::assertEquals(
      Response::HTTP_FOUND,
      $this->client->getResponse()->getStatusCode()
    );
  }
  public function testUpdateCommentIsUp()
  {
    $this->client = static::createClient();
    $this->client->request('GET', '/update_comment/2/720');
    
    static::assertEquals(
      Response::HTTP_FOUND,
      $this->client->getResponse()->getStatusCode()
    );
  }
}