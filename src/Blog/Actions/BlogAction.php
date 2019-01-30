<?php

namespace App\Blog\Actions;

use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface;

class BlogAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Router
     */
    private $router;
    /**
     * @var PostTable
     */
    private $postTable;

    use RouterAwareAction;

    /**
     * BlogAction constructor.
     * @param RendererInterface $renderer
     * @param PostTable $postTable
     * @param Router $router
     */
    public function __construct(RendererInterface $renderer, Router $router, PostTable $postTable)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postTable = $postTable;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function __invoke(Request $request)
    {
        $slug = $request->getAttribute('id');

        if ($slug) {
            return $this->show($request);
        }
        return $this->index();
    }

    public function index(): string
    {
        $posts = $this->postTable->findPaginated();

        return $this->renderer->render('@blog/index', [
            'posts' => $posts
        ]);
    }

    /**
     * Show single post
     *
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function show(Request $request)
    {
        $slug = $request->getAttribute('slug');

        $post =$this->postTable->find($request->getAttribute('id'));

        if ($post->slug !== $slug) {
            return $this->redirect('blog.show', [
                'slug' => $post->slug,
                'id' => $post->id
            ]);
        }

        return $this->renderer->render('@blog/show', [
            'post' => $post
        ]);
    }
}
