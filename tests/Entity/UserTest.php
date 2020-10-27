<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;


class UserTest extends TestCase
{
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