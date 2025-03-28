<?php
namespace Tests\Controllers;

use App\Controllers\UploadController;
use App\Core\Uploader;
use App\Models\Csv;
use PHPUnit\Framework\TestCase;
use Exception;

class UploadControllerTest extends TestCase
{
    private $validFile;
    private $invalidFile;

    protected function setUp(): void
    {
        $this->validFile = [
            'name' => 'test.csv',
            'type' => 'text/csv',
            'tmp_name' => __DIR__ . '/../fixtures/valid.csv',
            'error' => UPLOAD_ERR_OK,
            'size' => 1024
        ];

        $this->invalidFile = [
            'name' => 'test.pdf',
            'type' => 'application/pdf',
            'tmp_name' => __DIR__ . '/../fixtures/valid.csv',
            'error' => UPLOAD_ERR_OK,
            'size' => 1024
        ];
    }

    public function testHandleUploadSuccess()
    {
        $controller = new UploadController();
        $result = $controller->handleUpload($this->validFile, ',');
        
        $this->assertIsArray($result);
        $this->assertEquals('success', $result['status']);
        $this->assertArrayHasKey('data', $result);
    }

    public function testHandleUploadInvalidFile()
    {
        $this->expectException(\Exception::class);
        
        $tempFile = tempnam(sys_get_temp_dir(), 'test');
        file_put_contents($tempFile, 'fake content - not a CSV');
        
        $this->invalidFile['tmp_name'] = $tempFile;

        $controller = new UploadController();
        
        try {
            $controller->handleUpload($this->invalidFile, ',');
        } finally {
            unlink($tempFile);
        }
    }

    public function testHandleUploadWithMock()
    {
        $uploaderMock = $this->createMock(Uploader::class);
        $uploaderMock->method('validate')->willReturn(true);
        $uploaderMock->method('getTempPath')->willReturn(__DIR__ . '/../fixtures/valid.csv');
        
        $csvMock = $this->createMock(Csv::class);
        $csvMock->method('process')->willReturn([
            ['codigo' => 'ABC123', 'nome' => 'Produto Teste', 'preco' => 'R$ 10,00']
        ]);
        
        // Criar uma instância do controller com os mocks injetados
        $controller = new class($uploaderMock, $csvMock) extends UploadController {
            private $uploader;
            private $csv;
            
            public function __construct($uploader, $csv)
            {
                $this->uploader = $uploader;
                $this->csv = $csv;
            }
            
            protected function createUploader($file): Uploader
            {
                return $this->uploader;
            }
            
            protected function createCsv($path, $delimiter): Csv
            {
                return $this->csv;
            }
        };
        
        $result = $controller->handleUpload($this->validFile, ',');
        
        $this->assertEquals('success', $result['status']);
        $this->assertCount(1, $result['data']);
    }
}
