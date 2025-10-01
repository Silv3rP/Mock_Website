<?php

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\User; 

class UserTest extends TestCase
{
    private $mockConn; // Mock database connection

    protected function setUp(): void
    {
        // Create a new mock mysqli object before each test
        $this->mockConn = $this->createMock(\mysqli::class);
    }

    public function testLoginSuccess(): void
    {
        // Test successful login
        $email = 'test@example.com';
        $password = 'password';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userId = 1;
        $role = 'user';


        // Mock the database prepare method
        $mockStmt = $this->createMock(\mysqli_stmt::class);
        $this->mockConn->expects($this->once())->method('prepare')->willReturn($mockStmt);

        // Mock binding parameters
        $mockStmt->expects($this->once())->method('bind_param')->willReturn(true);

        // Mock execution
        $mockStmt->expects($this->once())->method('execute')->willReturn(true);

        // Mock getting the result
        $mockResult = $this->createMock(\mysqli_result::class);
        $mockStmt->expects($this->once())->method('get_result')->willReturn($mockResult);

        // Mock fetching the user data
        $mockResult->expects($this->once())->method('fetch_assoc')->willReturn(['id' => $userId, 'password' => $hashedPassword, 'role' => $role]);

        // Create the User object with the mock connection
        $user = new User($this->mockConn);

        // Call the login method
        $loggedInUserId = $user->login($email, $password);

        // Assert that the returned user ID matches the expected ID
        $this->assertSame(['id' => $userId, 'role' => $role], $loggedInUserId);
    }

    public function testLoginFailureWrongPassword(): void
    {
        // Test login failure due to incorrect password
        $email = 'test@example.com';
        $correctPassword = 'correct';
        $wrongPassword = 'wrong';
        $hashedPassword = password_hash($correctPassword, PASSWORD_DEFAULT);

        // Mock database interactions (similar to success case)
        $mockStmt = $this->createMock(\mysqli_stmt::class);
        $this->mockConn->expects($this->once())->method('prepare')->willReturn($mockStmt);
        $mockStmt->expects($this->once())->method('bind_param')->willReturn(true);
        $mockStmt->expects($this->once())->method('execute')->willReturn(true);
        $mockResult = $this->createMock(\mysqli_result::class);
        $mockStmt->expects($this->once())->method('get_result')->willReturn($mockResult);
        $mockResult->expects($this->once())->method('fetch_assoc')->willReturn(['id' => 1, 'password' => $hashedPassword]);

        $user = new User($this->mockConn);
        $loggedInUserId = $user->login($email, $wrongPassword);

        // Assert that login returns null (failure)
        $this->assertNull($loggedInUserId);
    }

    public function testLoginFailureUserNotFound(): void
    {
        // Test login failure when the user with the given email is not found
        $email = 'no@example.com';
        $password = 'any';

        // Mock database interactions to simulate no user found
        $mockStmt = $this->createMock(\mysqli_stmt::class);
        $this->mockConn->expects($this->once())->method('prepare')->willReturn($mockStmt);
        $mockStmt->expects($this->once())->method('bind_param')->willReturn(true);
        $mockStmt->expects($this->once())->method('execute')->willReturn(true);
        $mockResult = $this->createMock(\mysqli_result::class);
        $mockStmt->expects($this->once())->method('get_result')->willReturn($mockResult);
        $mockResult->expects($this->once())->method('fetch_assoc')->willReturn(null); // Simulate no user

        $user = new User($this->mockConn);
        $loggedInUserId = $user->login($email, $password);

        // Assert that login returns null
        $this->assertNull($loggedInUserId);
    }

    public function testLoginPrepareError(): void
    {
        // Test the case where the database prepare statement fails
        $email = 'test@example.com';
        $password = 'password';

        // Mock the prepare method to return false (indicating an error)
        $this->mockConn->expects($this->once())->method('prepare')->willReturn(false);

        $user = new User($this->mockConn);
        $loggedInUserId = $user->login($email, $password);

        // Assert that login returns null due to the database error
        $this->assertNull($loggedInUserId);
    }

    // Validation tests
    public function testValidateEmail() {
        $this->assertTrue(User::validateEmail('user@example.com'));
        $this->assertFalse(User::validateEmail('user@gmail.com'));
        $this->assertFalse(User::validateEmail('user@'));
        $this->assertFalse(User::validateEmail('userexample.com'));
    }

    public function testValidatePassword() {
        $this->assertTrue(User::validatePassword('StrongP@ss1'));
        $this->assertFalse(User::validatePassword('weakpass'));
        $this->assertFalse(User::validatePassword('Short1!'));
        $this->assertFalse(User::validatePassword('NoSpecialChar1'));
        $this->assertFalse(User::validatePassword('nouppercase1!'));
        $this->assertFalse(User::validatePassword('NOLOWERCASE1!'));
    }

    public function testValidateRole() {
        $this->assertTrue(User::validateRole('user'));
        $this->assertTrue(User::validateRole('admin'));
        $this->assertFalse(User::validateRole('superuser'));
        $this->assertFalse(User::validateRole(''));
    }
}