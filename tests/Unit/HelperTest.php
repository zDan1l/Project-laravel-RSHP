<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\StringHelper;
use App\Helpers\ValidationHelper;

class HelperTest extends TestCase
{
    /**
     * Test StringHelper::formatName
     */
    public function test_format_name()
    {
        $result = StringHelper::formatName('john doe');
        $this->assertEquals('John Doe', $result);

        $result = StringHelper::formatName('  MARY JANE  ');
        $this->assertEquals('Mary Jane', $result);

        $result = StringHelper::formatName('alice');
        $this->assertEquals('Alice', $result);
    }

    /**
     * Test StringHelper::formatNamaJenisHewan
     */
    public function test_format_nama_jenis_hewan()
    {
        $result = StringHelper::formatNamaJenisHewan('anjing');
        $this->assertEquals('Anjing', $result);

        $result = StringHelper::formatNamaJenisHewan('kucing persia');
        $this->assertEquals('Kucing Persia', $result);

        $result = StringHelper::formatNamaJenisHewan('BURUNG');
        $this->assertEquals('Burung', $result);
    }

    /**
     * Test StringHelper::sanitize
     */
    public function test_sanitize()
    {
        $result = StringHelper::sanitize('<script>alert("xss")</script>');
        $this->assertStringNotContainsString('<script>', $result);

        $result = StringHelper::sanitize('Normal Text');
        $this->assertEquals('Normal Text', $result);
    }

    /**
     * Test StringHelper::slugify
     */
    public function test_slugify()
    {
        $result = StringHelper::slugify('Hello World');
        $this->assertEquals('hello-world', $result);

        $result = StringHelper::slugify('Kucing Persia 123');
        $this->assertEquals('kucing-persia-123', $result);

        $result = StringHelper::slugify('Test   Multiple   Spaces');
        $this->assertEquals('test-multiple-spaces', $result);
    }

    /**
     * Test StringHelper::isAlphaSpace
     */
    public function test_is_alpha_space()
    {
        $this->assertTrue(StringHelper::isAlphaSpace('John Doe'));
        $this->assertTrue(StringHelper::isAlphaSpace('Mary'));
        $this->assertFalse(StringHelper::isAlphaSpace('John123'));
        $this->assertFalse(StringHelper::isAlphaSpace('Test@email'));
    }

    /**
     * Test StringHelper::limit
     */
    public function test_limit()
    {
        $text = 'This is a very long text that needs to be limited';
        $result = StringHelper::limit($text, 20);
        $this->assertEquals('This is a very long ...', $result);

        $shortText = 'Short';
        $result = StringHelper::limit($shortText, 20);
        $this->assertEquals('Short', $result);
    }

    /**
     * Test ValidationHelper::validateNamaJenisHewan - Valid cases
     */
    public function test_validate_nama_jenis_hewan_valid()
    {
        $result = ValidationHelper::validateNamaJenisHewan('Anjing');
        $this->assertTrue($result['valid']);
        $this->assertEquals('Valid', $result['message']);

        $result = ValidationHelper::validateNamaJenisHewan('Kucing Persia');
        $this->assertTrue($result['valid']);
    }

    /**
     * Test ValidationHelper::validateNamaJenisHewan - Empty
     */
    public function test_validate_nama_jenis_hewan_empty()
    {
        $result = ValidationHelper::validateNamaJenisHewan('');
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('tidak boleh kosong', $result['message']);

        $result = ValidationHelper::validateNamaJenisHewan('   ');
        $this->assertFalse($result['valid']);
    }

    /**
     * Test ValidationHelper::validateNamaJenisHewan - Too long
     */
    public function test_validate_nama_jenis_hewan_too_long()
    {
        $longText = str_repeat('A', 101);
        $result = ValidationHelper::validateNamaJenisHewan($longText);
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('maksimal 100 karakter', $result['message']);
    }

    /**
     * Test ValidationHelper::validateNamaJenisHewan - Invalid characters
     */
    public function test_validate_nama_jenis_hewan_invalid_characters()
    {
        $result = ValidationHelper::validateNamaJenisHewan('Anjing123');
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('hanya boleh berisi huruf dan spasi', $result['message']);

        $result = ValidationHelper::validateNamaJenisHewan('Test@#$');
        $this->assertFalse($result['valid']);
    }

    /**
     * Test ValidationHelper::sanitizeInput
     */
    public function test_sanitize_input()
    {
        $data = [
            'name' => '  John Doe  ',
            'email' => '<script>test@email.com</script>',
            'age' => 25,
        ];

        $result = ValidationHelper::sanitizeInput($data);

        $this->assertEquals('John Doe', $result['name']);
        $this->assertStringNotContainsString('<script>', $result['email']);
        $this->assertEquals(25, $result['age']);
    }

    /**
     * Test global helper function format_nama
     */
    public function test_global_format_nama()
    {
        $result = format_nama('john doe');
        $this->assertEquals('John Doe', $result);
    }

    /**
     * Test global helper function format_nama_jenis_hewan
     */
    public function test_global_format_nama_jenis_hewan()
    {
        $result = format_nama_jenis_hewan('anjing');
        $this->assertEquals('Anjing', $result);
    }

    /**
     * Test global helper function slugify
     */
    public function test_global_slugify()
    {
        $result = slugify('Hello World');
        $this->assertEquals('hello-world', $result);
    }

    /**
     * Test global helper function validate_nama_jenis_hewan
     */
    public function test_global_validate_nama_jenis_hewan()
    {
        $result = validate_nama_jenis_hewan('Anjing');
        $this->assertTrue($result['valid']);

        $result = validate_nama_jenis_hewan('123');
        $this->assertFalse($result['valid']);
    }
}
