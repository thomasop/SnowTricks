<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;
use PHPUnit\Framework\TestCase;

class TrickControllerTest extends WebTestCase
{
    private $client = null;
  
  public function setUp()
  {
    $this->client = static::createClient();
  }
  
  public function testHomepageIsUp()
  {
    $this->client->request('GET', '/');
    
    static::assertEquals(
      Response::HTTP_OK,
      $this->client->getResponse()->getStatusCode()
    );
  }

  public function testAddTrickIsUp()
  {
    $this->client->request('GET', '/add_trick');
    
    static::assertEquals(
      Response::HTTP_OK,
      $this->client->getResponse()->getStatusCode()
    );
  }

  public function testDeleteTrickIsUp()
  {
    $this->client->request('GET', '/delete_trick/1');
    
    static::assertEquals(
      Response::HTTP_FOUND,
      $this->client->getResponse()->getStatusCode()
    );
  }

  public function testUpdateTrickIsUp()
  {
    $this->client->request('POST', '/update_trick/2');
    //dd($this->client->getResponse()->getStatusCode());
    static::assertEquals(
      Response::HTTP_OK,
      $this->client->getResponse()->getStatusCode()
    );
  }
}