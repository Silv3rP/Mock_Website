<?php
use PHPUnit\Framework\TestCase;

// Mock the database connection (for testing purposes)
class MockDB {
    public $insert_success = true;  // Controls the result of the insert
    public $error_message = "";
    public function prepare($query) { return $this; }
    public function bind_param($types, ...$vars) {}
    public function execute() { return $this->insert_success; }
    public function close() {}
    public function __construct() {}
}

// Mock header/footer includes
define('MOCK_TEMPLATES', true);

class ShippingPathTest extends TestCase {
    private $connBackup;
    private $mockConn;
    private $serverBackup;
    private $sessionBackup;

    protected function setUp(): void {
        // Backup globals
        $this->serverBackup = $_SERVER;
        $this->sessionBackup = $_SESSION ?? [];
        // Mock DB
        $this->mockConn = new MockDB();
        $GLOBALS['conn'] = $this->mockConn;
        // Prevent template includes
        if (!function_exists('include_template')) {
            function include_template($file) {}
        }
    }



    private function runShipping($cart, $postData, $dbInsertSuccess = true) {
        $_SESSION['cart'] = $cart;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = $postData;
        $this->mockConn->insert_success = $dbInsertSuccess;
        // Mock template includes
        $GLOBALS['__mock_templates'] = true;
        // Patch includes in shipping.php
        $shipping = file_get_contents(__DIR__ . '/../shipping.php');
        $shipping = preg_replace('/include\(["\']templates\/header.php["\']\);/', '', $shipping);
        $shipping = preg_replace('/include\(["\']templates\/footer.php["\']\);/', '', $shipping);
        // Patch DB connection
        $shipping = preg_replace('/\$conn = new mysqli\([^;]+;/', '', $shipping);
        // Patch session_start
        $shipping = preg_replace('/session_start\(\);/', '', $shipping);
        ob_start();
        eval('?>' . $shipping);
        $output = ob_get_clean();
        $cart_content = $_SESSION['cart'] ?? [];
        $_POST = [];
        $_SESSION['cart'] = [];
        return [$output, $cart_content];
    }

    // Test cases
    public function testPageLoadCartHasItems() {
        $cart = [1 => ['name' => 'Product A', 'price' => 10, 'quantity' => 2]];
        list($output, $cart) = $this->runShipping($cart, []);
        $this->assertStringContainsString('Product A', $output);
    }

    public function testIncreaseQuantity() {
        $cart = [1 => ['name' => 'Product A', 'price' => 10, 'quantity' => 2]];
        list($output, $final_cart) = $this->runShipping($cart, ['item_id' => 1, 'increase' => true]);
        $this->assertEquals(3, $final_cart[1]['quantity']);
        $this->assertStringContainsString('3', $output);
    }

    public function testDecreaseQuantityAboveOne() {
        $cart = [1 => ['name' => 'Product A', 'price' => 10, 'quantity' => 2]];
        list($output, $final_cart) = $this->runShipping($cart, ['item_id' => 1, 'decrease' => true]);
        $this->assertEquals(1, $final_cart[1]['quantity']);
        $this->assertStringContainsString('1', $output);
    }

    public function testDecreaseQuantityToZeroRemovesItem() {
        $cart = [1 => ['name' => 'Product A', 'price' => 10, 'quantity' => 1]];
        list($output, $final_cart) = $this->runShipping($cart, ['item_id' => 1, 'decrease' => true]);
        $this->assertEmpty($final_cart);
        $this->assertStringContainsString('Your cart is empty.', $output);
    }

    public function testRemoveItem() {
        $cart = [1 => ['name' => 'Product A', 'price' => 10, 'quantity' => 2]];
        list($output, $final_cart) = $this->runShipping($cart, ['item_id' => 1, 'remove' => true]);
        $this->assertEmpty($final_cart);
        $this->assertStringContainsString('Your cart is empty.', $output);
    }

    public function testCheckoutWithItems() {
        $cart = [1 => ['name' => 'Product A', 'price' => 10, 'quantity' => 2]];
        list($output, $final_cart) = $this->runShipping($cart, ['checkout' => true]);
        $this->assertEmpty($final_cart);
        $this->assertStringContainsString('Order placed successfully!', $output);
    }

    public function testCheckoutWithDbError() {
        $cart = [1 => ['name' => 'Product A', 'price' => 10, 'quantity' => 2]];
        list($output, $final_cart) = $this->runShipping($cart, ['checkout' => true], false);
        $this->assertEmpty($final_cart);
        $this->assertStringContainsString('Order placed successfully!', $output);
    }
}
?>
