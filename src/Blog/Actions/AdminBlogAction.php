<?php

namespace App\Blog\Actions;

use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface;

class AdminBlogAction
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
        if ($request->getAttribute('id')) {
            return $this->edit($request);
        }
        return $this->index($request);
    }

    public function index(Request $request): string
    {
        $params = $request->getQueryParams();
        $items = $this->postTable->findPaginated(12, $params['p'] ?? 1);

        return $this->renderer->render('@blog/admin/index', [
            'items' => $items
        ]);
    }

    /**
     * Post Edit
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function edit(Request $request)
    {
        $id = $request->getAttribute('id');
        $item = $this->postTable->find($id);

        if ($request->getMethod() === 'POST') {
            // Filter only needed values
            $params = array_filter($request->getParsedBody(), function ($key) {
                return in_array($key, ['name', 'content', 'slug']);
            }, ARRAY_FILTER_USE_KEY);
            // Then update
            $this->postTable->update($item->id, $params);
            // Redirect
            return $this->redirect('admin.blog.index');
        }

        return $this->renderer->render('@blog/admin/edit', [
            'item' => $item
        ]);
    }
}
