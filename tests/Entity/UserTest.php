<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;



class UserTest extends KernelTestCase
{

	public function testUser()
	{
		$name = "okfl";
		$user = (new User())
		->setPseudo($name)
		->setPassword("dfdgggddggdg")
		->setEmail("tdss33@hotmail.com");
		self::bootKernel();
		$error = self::$container->get('validator')->validate($user);
		$this->assertCount(0, $error);
	}

	public function testPseudoUser()
	{
		$user = new User();
		$pdeudo = "Test pseudo";

		$user->setPseudo($pdeudo);
		$this->assertEquals("Test pseudo", $user->getPseudo());
	}

	public function testPassword()
	{
		$user = new User();
		$password = "Test password";

		$user->setPassword($password);
		$this->assertEquals("Test password", $user->getPassword());
	}

	public function testEmail()
	{
		$user = new User();
		$email = "Test email";

		$user->setEmail($email);
		$this->assertEquals("Test email", $user->getEmail());
    }

    public function testRoles()
	{
		$user = new User();
		$this->assertEquals(['ROLE_USER'], $user->getRoles());
	}
}