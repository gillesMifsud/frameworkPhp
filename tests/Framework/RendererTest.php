<?php

namespace Tests\Framework;

use Framework\Renderer;
use PHPUnit\Framework\TestCase;

class RendererTest extends TestCase
{
    /**
     * @var Renderer
     */
    private $renderer;

    public function setUp()
    {
        $this->renderer = new Renderer();
        $this->renderer->addPath(__DIR__ . '/views');
    }

    /** @test */
    public function renderRightView()
    {
        $this->renderer->addPath('blog', __DIR__ . '/views');
        $content = $this->renderer->render('@blog/demo');
        $this->assertEquals('Salut les gens', $content);
    }

    /** @test */
    public function renderDefaultPath()
    {
        $content = $this->renderer->render('demo');
        $this->assertEquals('Salut les gens', $content);
    }

    public function testRenderWithParams()
    {
        $content = $this->renderer->render('demoparams', ['name' => 'Toto']);
        $this->assertEquals('Salut Toto', $content);
    }

    public function testGlobalParams()
    {
        $this->renderer->addGlobal('name', 'Toto');
        $content = $this->renderer->render('demoparams');
        $this->assertEquals('Salut Toto', $content);
    }
}
