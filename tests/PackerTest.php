<?php

namespace CrCms\Microservice\Bridging\Tests;

use CrCms\Microservice\Bridging\DataPacker;
use CrCms\Microservice\Bridging\Packer\JsonPacker;
use PHPUnit\Framework\TestCase;

class PackerTest extends TestCase
{
    /**
     * @var DataPacker
     */
    protected $packer;

    public function setUp()
    {
        parent::setUp();

        $this->packer = new DataPacker(new JsonPacker());
    }

    public function testEmptyValuePack()
    {
        $data = ['call' => 'test', 'data'=>['x'=>1]];
        $result = $this->packer->pack($data);

        $this->assertNotEmpty($result);

        return $result;
    }

    /**
     * @depends testEmptyValuePack
     */
    public function testEmptyValueUnpack(string $data)
    {
        $data = $this->packer->unpack($data);
        $this->assertArrayHasKey('call', $data);
        $this->assertEquals('test', $data['call']);
        $this->assertEquals(['x'=>1], $data['data']);
    }

    public function testNotEmptyPack()
    {
        $data = ['call' => 'test', 'data' => ['key' => 'value']];
        $value = $this->packer->pack($data);
        $this->assertNotEmpty($value);

        return $value;
    }

    /**
     * @depends testNotEmptyPack
     */
    public function testNotEmptyValueUnpack(string $value)
    {
        $data = $this->packer->unpack($value);
        $this->assertArrayHasKey('call', $data);
        $this->assertArrayHasKey('data', $data);
        $this->assertEquals('test', $data['call']);
        $this->assertEquals('value', $data['data']['key']);
    }

    public function testNotEncryptPack()
    {
        $data = ['call' => 'test', 'data' => ['key' => 'value']];
        $value = $this->packer->pack($data);
        $this->assertNotEmpty($value);

        return $value;
    }

    /**
     * @depends testNotEncryptPack
     *
     * @param string $value
     */
    public function testNotEncryptUnpack(string $value)
    {
        $data = $this->packer->unpack($value);
        $this->assertArrayHasKey('call', $data);
        $this->assertArrayHasKey('data', $data);
        $this->assertEquals('test', $data['call']);
        $this->assertEquals('value', $data['data']['key']);
    }

    public function testEmptyNotEncryptPack()
    {
        $data = ['call' => 'test'];
        $value = $this->packer->pack($data);
        $this->assertNotEmpty($value);

        return $value;
    }

    /**
     * @depends testEmptyNotEncryptPack
     *
     * @param $value
     */
    public function testEmptyNotEncryptUnpack($value)
    {
        $data = $this->packer->unpack($value);
        $this->assertArrayHasKey('call', $data);
        $this->assertEquals(1, count($data));
        $this->assertEquals(['call' => 'test'], $data);
    }
}
