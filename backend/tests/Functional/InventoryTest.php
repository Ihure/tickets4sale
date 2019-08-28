<?php
namespace Tests\Functional;

use Illuminate\Http\UploadedFile;

class InventoryTest extends BaseTestCase{

    private $uploadedFile = __DIR__ . '/test.csv';

    /**
     * Test that the index route returns a response containing the text 'hello' but not 'SlimFramework'
     */
    public function testGetHomepageWithoutName(){

        $response = $this->runApp('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Hello', (string)$response->getBody());
        $this->assertStringNotContainsString('SlimFramework', (string)$response->getBody());
    }

    public function testScenario2() {

        $file = new UploadedFile(
            __DIR__.'/test.csv',
            'test.csv',
            'text/csv',
            filesize(__DIR__.'/test.csv'),
            0
        );

        $response = $this->runApp('POST', '/inventory/checkInventory', [
            'show_date' => '2017-08-15',
            'query_date' => '2017-08-01',
            'csv' => $file
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('inventory', $data);
        $this->assertEquals(50, $data['inventory'][0]['shows'][0]['tickets_left']);
    }

    public function testScenario1() {

        $response = $this->runApp('POST', '/inventory/checkInventory', [
            'show_date' => '2017-07-01',
            'query_date' => '2017-01-01'
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('inventory', $data);
        $this->assertEquals(200, $data['inventory'][0]['shows'][0]['tickets_left']);
    }
}