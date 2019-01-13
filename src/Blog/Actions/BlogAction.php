<?php

namespace App\Blog\Actions;

use Framework\Renderer\RendererInterface;
use Psr\Http\Message\RequestInterface as Request;

class BlogAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;


    /**
     * BlogAction constructor.
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function __invoke(Request $request)
    {
        $slug = $request->getAttribute('slug');

        if ($slug) {
            return $this->show($slug);
        }
        return $this->index();
    }

    public function index(): string
    {
        return $this->renderer->render('@blog/index');
    }

    public function show(string $slug): string
    {
        return $this->renderer->render('@blog/show', [
            'slug' => $slug
        ]);
    }
}
