<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Menu;

class MenuTest extends TestCase
{
    protected $menu;

    protected function setUp(): void
    {
        //Mysqli connection to the database
        $mysqli = new \mysqli("localhost", "root", "", "fastfood_db", 3307);
        //menu object creation
        $this->menu = new Menu($mysqli);
    }

    public function testGetAllItems()
    {
        $allItems = $this->menu->getAllItems();
        $this->assertIsArray($allItems);
        $this->assertNotEmpty($allItems, "Menu items should not be empty.");
    }

    public function testGetItemById()
    {
        $item = $this->menu->getItemById(1);
        $this->assertIsArray($item);
        $this->assertEquals("Los Pollos Locos Wings", $item['item_name']);
    }

    public function testGetInvalidItemById()
    {
        $item = $this->menu->getItemById(9999);
        $this->assertNull($item);
    }

    public function testSearchItemsFound()
    {
        $results = $this->menu->searchItems('Wings');
        $this->assertIsArray($results);
        $this->assertNotEmpty($results, 'Should find menu items matching the search.');
        $this->assertStringContainsStringIgnoringCase('Wings', $results[0]['item_name']);
    }

    public function testSearchItemsNotFound()
    {
        $results = $this->menu->searchItems('NonExistentFood');
        $this->assertIsArray($results);
        $this->assertEmpty($results, 'Should return empty array for no matches.');
    }
}
