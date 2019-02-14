<?php

namespace App\Blog\Actions;

use App\Blog\Table\PostTable;
use Framework\Actions\RouterAwareAction;
use Framework\Renderer\RendererInterface;
use Framework\Router;
use Framework\Session\FlashService;
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

    /**
     * @var FlashService
     */
    private $flashService;

    use RouterAwareAction;

    /**
     * BlogAction constructor.
     * @param RendererInterface $renderer
     * @param Router $router
     * @param PostTable $postTable
     * @param FlashService $flashService
     */
    public function __construct(
        RendererInterface $renderer,
        Router $router,
        PostTable $postTable,
        FlashService $flashService)
    {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->postTable = $postTable;
        $this->flashService = $flashService;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function __invoke(Request $request)
    {
        if ($request->getMethod() === 'DELETE') {
            return $this->delete($request);
        }
        if (substr((string)$request->getUri(), -3) === 'new') {
            return $this->create($request);
        }
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
            $params = $this->getParams($request);
            // Update updated_at entity field
            $params['updated_at'] = date('Y-m-d H:i:s');
            // Then update
            $this->postTable->update($item->id, $params);
            // Set flash message
            $this->flashService->success('Article modifié avec succès');
            // Redirect
            return $this->redirect('blog.admin.index');
        }

        return $this->renderer->render('@blog/admin/edit', [
            'item' => $item
        ]);
    }

    /**
     * New post
     * @param Request $request
     * @return ResponseInterface|string
     */
    public function create(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            // Filter only needed values
            $params = $this->getParams($request);
            // Generate missing fields
            $params = array_merge($params, [
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ]);
            // Then create
            $this->postTable->insert($params);
            // Redirect
            return $this->redirect('blog.admin.index');
        }

        return $this->renderer->render('@blog/admin/create', [

        ]);
    }

    /**
     * @param Request $request
     * @return ResponseInterface
     */
    private function delete(Request $request)
    {
        $id = $request->getAttribute('id');
        $this->postTable->delete($id);
        return $this->redirect('blog.admin.index');
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getParams(Request $request)
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'content', 'slug']);
        }, ARRAY_FILTER_USE_KEY);
    }
}
