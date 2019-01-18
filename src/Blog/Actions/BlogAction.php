<?php

namespace App\Blog\Actions;

use Framework\Renderer\RendererInterface;
use Framework\Router;
use GuzzleHttp\Psr7\Response;
use PDO;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface;

class BlogAction
{
    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var PDO
     */
    private $pdo;
    /**
     * @var Router
     */
    private $router;


    /**
     * BlogAction constructor.
     * @param RendererInterface $renderer
     * @param PDO $pdo
     * @param Router $router
     */
    public function __construct(RendererInterface $renderer, PDO $pdo, Router $router)
    {
        $this->renderer = $renderer;
        $this->pdo = $pdo;
        $this->router = $router;
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
        $posts = $this->pdo
            ->query('SELECT * FROM posts ORDER BY created_at DESC LIMIT 10')
            ->fetchAll();

        return $this->renderer->render('@blog/index', [
            'posts' => $posts
        ]);
    }

    /**
     * Show single post
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function show(Request $request)
    {
        $slug = $request->getAttribute('slug');

        $query = $this->pdo->prepare('SELECT * FROM posts WHERE id = ?');
        $query->execute([$request->getAttribute('id')]);
        $post = $query->fetch();

        if ($post->slug !== $slug) {

            $redirectUri = $this->router->generateUri('blog.show', [
                'slug' => $post->slug,
                'id' => $post->id
            ]);

            return (new Response())
                ->withStatus(301)
                ->withHeader('location', $redirectUri);
        }


        return $this->renderer->render('@blog/show', [
            'post' => $post
        ]);
    }
}
