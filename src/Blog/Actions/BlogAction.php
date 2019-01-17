<?php

namespace App\Blog\Actions;

use Framework\Renderer\RendererInterface;
use PDO;
use Psr\Http\Message\RequestInterface as Request;

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
     * BlogAction constructor.
     * @param RendererInterface $renderer
     * @param PDO $pdo
     */
    public function __construct(RendererInterface $renderer, PDO $pdo)
    {
        $this->renderer = $renderer;
        $this->pdo = $pdo;
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
        $posts = $this->pdo
            ->query('SELECT * FROM posts ORDER BY created_at DESC LIMIT 10')
            ->fetchAll();

        return $this->renderer->render('@blog/index', compact($posts));
    }

    public function show(string $slug): string
    {
        return $this->renderer->render('@blog/show', [
            'slug' => $slug
        ]);
    }
}
