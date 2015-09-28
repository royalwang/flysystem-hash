<?php
use Emgag\Flysystem\Hash\HashPlugin;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

/**
 * Test class for hash plugin
 */
class HashPluginTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var $fs Filesystem
     */
    protected $fs;

    /**
     * Setup filesystem object
     */
    protected function setUp()
    {
        $this->fs = new Filesystem(new Local(__DIR__ . '/files'));
        $this->fs->addPlugin(new HashPlugin);
    }

    /**
     * @param $file
     * @param $algo
     * @param $expect
     * @dataProvider providerHashes
     */
    public function testHash($file, $algo, $expect)
    {
        if (function_exists('hash_equals')) {
            $this->assertTrue(hash_equals($expect, $this->fs->hash($file, $algo)));
        } else {
            $this->assertEquals($expect, $this->fs->hash($file, $algo));
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnsupportedAlgo()
    {
        $this->fs->hash('file1.txt', 'supersecretnsaalgorithm');
    }

    /**
     * @expectedException League\Flysystem\FileNotFoundException
     */
    public function testFileNotFound()
    {
        $this->fs->hash('filenotfound');
    }

    /**
     * @return array
     */
    public function providerHashes()
    {
        return [
            ['file1.txt', 'sha256', 'c147efcfc2d7ea666a9e4f5187b115c90903f0fc896a56df9a6ef5d8f3fc9f31'],
            ['file2.txt', 'sha256', '3377870dfeaaa7adf79a374d2702a3fdb13e5e5ea0dd8aa95a802ad39044a92f'],
            ['file1.txt', 'sha1', '60b27f004e454aca81b0480209cce5081ec52390'],
            ['file2.txt', 'sha1', 'cb99b709a1978bd205ab9dfd4c5aaa1fc91c7523'],
            ['file1.txt', 'md5', '826e8142e6baabe8af779f5f490cf5f5'],
            ['file2.txt', 'md5', '1c1c96fd2cf8330db0bfa936ce82f3b9']
        ];
    }


}
