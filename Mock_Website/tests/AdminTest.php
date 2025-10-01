<?php

namespace Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Admin;

class AdminTest extends TestCase
{
    private $mockConn;

    protected function setUp(): void
    {
        // Create a more realistic mock connection
        $this->mockConn = $this->createMock(\mysqli::class);
    }
    

    public function testManageMenuAddSuccess(): void
    {
        $action = 'add';
        $menuItem = [
            'item_name' => 'Test Burger',
            'price' => 9.99
        ];

        // Create a mock statement
        $mockStmt = $this->createMock(\mysqli_stmt::class);

        // Set up expectations
        $this->mockConn->expects($this->once())
            ->method('prepare')
            ->withAnyParameters()
            ->willReturn($mockStmt);

        $mockStmt->expects($this->once())
            ->method('bind_param')
            ->withAnyParameters()
            ->willReturn(true);

        $mockStmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $mockStmt->expects($this->once())
            ->method('close');

        $admin = new Admin($this->mockConn);
        $result = $admin->manageMenu($action, $menuItem);

        $this->assertTrue($result);
    }

    public function testManageMenuAddPrepareFailure(): void
    {
        $action = 'add';
        $menuItem = [
            'item_name' => 'Test Burger',
            'price' => 9.99
        ];

        $this->mockConn->expects($this->once())
            ->method('prepare')
            ->willReturn(false);

        $admin = new Admin($this->mockConn);
        $result = $admin->manageMenu($action, $menuItem);

        $this->assertFalse($result);
    }

    public function testManageMenuInvalidAction(): void
    {
        $action = 'invalid';
        $menuItem = [
            'item_name' => 'Test Burger',
            'price' => 9.99
        ];

        $admin = new Admin($this->mockConn);
        $result = $admin->manageMenu($action, $menuItem);

        $this->assertFalse($result);
    }
}