<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;
use PHPUnit\Framework\TestCase;

class SecurityControllerTest extends WebTestCase
{
    private $client = null;
  
  public function setUp()
  {
    $this->client = static::createClient();
  }
  
  public function testSecurityIsUp()
  {
    $this->client->request('GET', '/SnowTricks/login');
    
    static::assertEquals(
      Response::HTTP_OK,
      $this->client->getResponse()->getStatusCode()
    );
  }
  public function testSecurityIsDown()
  {
    $this->client->request('GET', '/SnowTricks/logout');
    //dd(Response::HTTP_OK);
    static::assertEquals(
      Response::HTTP_FOUND,
      
      $this->client->getResponse()->getStatusCode()
    );
  }
}