<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;
use PHPUnit\Framework\TestCase;

class CommentControllerTest extends WebTestCase
{
    private $client = null;
  
  public function setUp()
  {
    $this->client = static::createClient();
  }
  
  public function testCommentIsUp()
  {
    $this->client->request('GET', '/comment/1');
    
    static::assertEquals(
      Response::HTTP_OK,
      $this->client->getResponse()->getStatusCode()
    );
  }

  public function testAddCommentIsUp()
  {
    $this->client->request('GET', '/add_comment/1');
    
    static::assertEquals(
      Response::HTTP_OK,
      $this->client->getResponse()->getStatusCode()
    );
  }

  public function testDeleteCommentIsUp()
  {
    $this->client->request('GET', '/delete_comment/12/10');
    
    static::assertEquals(
      Response::HTTP_FOUND,
      $this->client->getResponse()->getStatusCode()
    );
  }
  public function testUpdateCommentIsUp()
  {
    $this->client->request('GET', '/update_comment/12/10');
    
    static::assertEquals(
      Response::HTTP_OK,
      $this->client->getResponse()->getStatusCode()
    );
  }
}