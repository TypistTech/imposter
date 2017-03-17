<?php
namespace TypistTech\Imposter;

use Illuminate\Filesystem\Filesystem;

/**
 * @coversDefaultClass \TypistTech\Imposter\Config
 */
class ConfigTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var string
     */
    private $json;

    /**
     * @var Config
     */
    private $config;

    /**
     * @covers \TypistTech\Imposter\Config
     */
    public function testExcludeImposter()
    {
        $actual = $this->config->getRequires();

        $this->assertNotContains('typisttech/imposter', $actual);
    }

    /**
     * @covers \TypistTech\Imposter\Config
     */
    public function testGetAutoloads()
    {
        $actual = $this->config->getAutoloads();

        $expected = [
            codecept_data_dir('i-am-simple-string'),
            codecept_data_dir('i-am-single-array'),
            codecept_data_dir('i-am-array-1'),
            codecept_data_dir('i-am-array-2'),
            codecept_data_dir('i-am-object-simple-string'),
            codecept_data_dir('i-am-object-single-array-single'),
            codecept_data_dir('i-am-object-single-array-1'),
            codecept_data_dir('i-am-object-single-array-2'),
            codecept_data_dir('i-am-object-array-single'),
            codecept_data_dir('i-am-object-array-1'),
            codecept_data_dir('i-am-object-array-2'),
            codecept_data_dir('i-am-object-array-3'),
        ];

        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \TypistTech\Imposter\Config
     */
    public function testGetAutoloadsInVendorDir()
    {
        $json   = codecept_data_dir('tmp-vendor/dummy/dummy-psr4/composer.json');
        $config = ConfigFactory::build($json, new Filesystem);

        $actual = $config->getAutoloads();

        $expected = [
            codecept_data_dir('tmp-vendor/dummy/dummy-psr4/src/'),
        ];

        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \TypistTech\Imposter\Config
     */
    public function testGetAutoloadsUniqueness()
    {
        $json   = codecept_data_dir('tmp-vendor/dummy/dummy-dependency/composer.json');
        $config = ConfigFactory::build($json, new Filesystem);

        $actual = $config->getAutoloads();

        $expected = [
            codecept_data_dir('tmp-vendor/dummy/dummy-dependency/src/'),
            codecept_data_dir('tmp-vendor/dummy/dummy-dependency/lib/'),
        ];

        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \TypistTech\Imposter\Config
     */
    public function testGetPackageDir()
    {
        $expected = codecept_data_dir();

        $actual = $this->config->getPackageDir();

        $this->assertSame($expected, $actual);
    }

    /**
     * @covers \TypistTech\Imposter\Config
     */
    public function testGetRequires()
    {
        $expected = [
            'dummy/dummy',
            'dummy/dummy-psr4',
        ];

        $actual = $this->config->getRequires();

        $this->assertSame($expected, $actual);
    }

    protected function _before()
    {
        $this->json   = codecept_data_dir('composer.json');
        $this->config = ConfigFactory::build($this->json, new Filesystem);
    }
}
