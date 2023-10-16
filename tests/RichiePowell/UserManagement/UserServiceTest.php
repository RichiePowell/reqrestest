<?php

namespace RichiePowell\UserManagement\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use RichiePowell\UserManagement\UserService;
use RichiePowell\UserManagement\User;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private Client $httpClient;
    private array $users;

    protected function setUp(): void
    {
        $this->httpClient = new Client();
        $this->userService = new UserService($this->httpClient);
    }

    //testGetUserById
    public function testGetUserById()
    {
        $user = $this->userService->getUserById(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->getId());
        $this->assertEquals("George Bluth", $user->getName());
    }

    public function testGetUserByIdNotFound()
    {
        $this->expectException(\Exception::class);
        $this->userService->getUserById(99);
    }

    public function testGetUsers()
    {
        $users = $this->userService->getUsers();

        $this->assertIsArray($users);
        $this->assertCount(3, $users);

        $this->assertInstanceOf(User::class, $users[0]);
        $this->assertEquals(1, $users[0]->getId());
        $this->assertEquals("George Bluth", $users[0]->GetName());

        $this->assertInstanceOf(User::class, $users[1]);
        $this->assertEquals(2, $users[1]->getId());
        $this->assertEquals("Janet Weaver", $users[1]->getName());
    }

    function testCreateUser()
    {
        $user = $this->userService->createUser("John Doe", "Developer");

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("John Doe", $user->getName());
        $this->assertEquals("Developer", $user->getJob());
    }
}