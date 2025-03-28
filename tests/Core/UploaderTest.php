<?php
namespace Tests\Core;

use App\Core\Uploader;
use PHPUnit\Framework\TestCase;
use LengthException;
use InvalidArgumentException;

class UploaderTest extends TestCase
{
    private $validFile;
    private $largeFile;
    private $invalidTypeFile;

    protected function setUp(): void
    {
        $this->validFile = [
            'name' => 'test.csv',
            'type' => 'text/csv',
            'tmp_name' => __DIR__ . '/../fixtures/valid.csv',
            'error' => UPLOAD_ERR_OK,
            'size' => 1024
        ];

        $this->largeFile = [
            'name' => 'large.csv',
            'type' => 'text/csv',
            'tmp_name' => __DIR__ . '/../fixtures/valid.csv',
            'error' => UPLOAD_ERR_OK,
            'size' => 10485761
        ];

        $this->invalidTypeFile = [
            'name' => 'test.pdf',
            'type' => 'application/pdf',
            'tmp_name' => __DIR__ . '/../fixtures/valid.csv',
            'error' => UPLOAD_ERR_OK,
            'size' => 1024
        ];
    }

    public function testValidateValidFile()
    {
        $uploader = new Uploader($this->validFile);
        $this->assertTrue($uploader->validate());
    }

    public function testValidateLargeFile()
    {
        $this->expectException(LengthException::class);
        $uploader = new Uploader($this->largeFile);
        $uploader->validate();
    }

    public function testValidateInvalidType()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $tempFile = tempnam(sys_get_temp_dir(), 'test');
        file_put_contents($tempFile, '%PDF-1.4 fake PDF content');
        
        $this->invalidTypeFile['tmp_name'] = $tempFile;
        $uploader = new Uploader($this->invalidTypeFile);
        
        try {
            $uploader->validate();
        } finally {
            unlink($tempFile);
        }
    }

    public function testValidateInvalidTypeWithMock()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $uploader = $this->getMockBuilder(Uploader::class)
            ->setConstructorArgs([$this->invalidTypeFile])
            ->onlyMethods(['getMimeType'])
            ->getMock();
        
        $uploader->method('getMimeType')
            ->willReturn('application/pdf');

        $uploader->validate();
    }

    public function testGetMimeType()
    {
        $uploader = new Uploader($this->validFile);
        $this->assertEquals('text/plain', $uploader->getMimeType());
    }

    public function testGetTempPath()
    {
        $uploader = new Uploader($this->validFile);
        $this->assertEquals($this->validFile['tmp_name'], $uploader->getTempPath());
    }
}
