<?php
namespace Tests\Models;

use App\Models\Csv;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use UnexpectedValueException;

class CsvTest extends TestCase
{
    private $validCsvPath;
    private $missingColumnsPath;

    protected function setUp(): void
    {
        $this->validCsvPath = __DIR__ . '/../fixtures/valid.csv';
        $this->missingColumnsPath = __DIR__ . '/../fixtures/missing_columns.csv';
    }

    public function testProcessValidFile()
    {
        $csv = new Csv($this->validCsvPath, ',');
        $result = $csv->process();
        
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('codigo', $result[0]);
        $this->assertArrayHasKey('nome', $result[0]);
        $this->assertArrayHasKey('preco', $result[0]);
    }

    public function testProcessWithMissingColumns()
    {
        $this->expectException(UnexpectedValueException::class);
        $csv = new Csv($this->missingColumnsPath, ',');
        $csv->process();
    }

    public function testProcessNonExistentFile()
    {
        $this->expectException(RuntimeException::class);
        $csv = new Csv('nonexistent.csv', ',');
        $csv->process();
    }

    public function testIsCopyAllowed()
    {
        $csv = new Csv($this->validCsvPath, ',');
        
        $reflection = new \ReflectionClass($csv);
        $method = $reflection->getMethod('isCopyAllowed');
        $method->setAccessible(true);
        
        $this->assertTrue($method->invoke($csv, 'ABC2DF'));
        $this->assertFalse($method->invoke($csv, 'ABC1DF'));
    }

    public function testIsNegativeNumber()
    {
        $csv = new Csv($this->validCsvPath, ',');
        
        $reflection = new \ReflectionClass($csv);
        $method = $reflection->getMethod('isNegativeNumber');
        $method->setAccessible(true);
        
        $this->assertTrue($method->invoke($csv, 'R$ -50,00'));
        $this->assertFalse($method->invoke($csv, 'R$ 50,00'));
    }

    public function testSortingByName()
    {
        $csv = new Csv($this->validCsvPath, ',');
        $result = $csv->process();
        
        $names = array_column($result, 'nome');
        $sortedNames = $names;
        sort($sortedNames);
        
        $this->assertEquals($sortedNames, $names);
    }
}
