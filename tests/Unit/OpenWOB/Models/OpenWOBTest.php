<?php declare(strict_types=1);

namespace Yard\OpenWOB\Tests\Models;

use Mockery as m;
use WP_Mock;
use Yard\OpenWOB\ElasticPress\ElasticPress;
use Yard\OpenWOB\Foundation\Config;
use Yard\OpenWOB\Foundation\Loader;
use Yard\OpenWOB\Foundation\Plugin;
use Yard\OpenWOB\Models\Item;
use Yard\OpenWOB\Models\OpenWOB;
use Yard\OpenWOB\Repository\OpenWOBRepository;
use Yard\OpenWOB\Tests\TestCase;

class OpenWOBTest extends TestCase
{
    protected function setUp(): void
    {
        WP_Mock::setUp();

        $this->config = m::mock(Config::class);
        $this->repository = m::mock(OpenWOBRepository::class);

        $this->plugin = m::mock(Plugin::class);
        $this->plugin->config = $this->config;
        $this->plugin->loader = m::mock(Loader::class);

        $this->item = m::mock(Item::class);

        $this->service = new ElasticPress($this->config, $this->repository);
    }

    protected function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function if_class_is_instance_of_OpenWOB_class()
    {
        \WP_Mock::userFunction('get_post_meta', [
            'times'  => 1,
            'return' => [],
        ]);

        $openwob = new OpenWOB([
            'ID' => 1
        ]);
        $this->assertInstanceOf(OpenWOB::class, $openwob);
    }
}
