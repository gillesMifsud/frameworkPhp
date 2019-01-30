<?php

namespace Tests\Framework;

use Framework\Router;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{

    /**
     * @var Router
     */
    private $router;

    public function setUp()
    {
        $this->router = new Router();
    }

    public function testGetMethod()
    {
        // Créé fausse requete contenant /blog
        $request = new ServerRequest('GET', '/blog');
        // Quand le router detecte /blog il lance la function et ensuite je sette le nom de l'url à blog
        $this->router->get('/blog', function (){return 'Hello';}, 'blog');
        // Verifie si la requete match un des url qui a été entrée
        $route = $this->router->match($request);
        // Si match, retourne un objet $route ou on recupere le nom avec getName() de la route qui a matché
        $this->assertEquals('blog', $route->getName());
        // Si match on recupère la function
        $this->assertEquals('Hello', call_user_func_array($route->getCallback(), [$request]));
    }

    public function testGetMethodIfURLDoesNotExist()
    {
        $request = new ServerRequest('GET', '/blog');
        $this->router->get('/bloggzgfgvu', function (){return 'Hello';}, 'blog');
        $route = $this->router->match($request);
        $this->assertEquals(null, $route);
    }

    public function testGetMethodWithParameters()
    {
        $request = new ServerRequest('GET', '/blog/mon-slug-8');
        $this->router->get('/blog', function (){return 'aezeae';}, 'posts');
        $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function (){return 'Hello';}, 'post.show');
        $route = $this->router->match($request);
        $this->assertEquals('post.show', $route->getName());
        $this->assertEquals('Hello', call_user_func_array($route->getCallback(), [$request]));
        $this->assertEquals([
            'slug' => 'mon-slug',
            'id' => '8',
            ], $route->getParams($request));

        // Test invalid url
        $route = $this->router->match(new ServerRequest('GET', '/blog/mon_slug-8'));
        $this->assertEquals(null, $route);
    }

    public function testGenerateUri()
    {
        $this->router->get('/blog', function (){return 'aezeae';}, 'posts');
        $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function (){return 'Hello';}, 'post.show');

        $uri = $this->router->generateUri('post.show', ['slug' => 'mon-article', 'id' => '18']);
        $this->assertEquals('/blog/mon-article-18', $uri);
    }

    public function testGenerateUriWithQueryParams()
    {
        $this->router->get('/blog', function (){return 'aezeae';}, 'posts');
        $this->router->get('/blog/{slug:[a-z0-9\-]+}-{id:\d+}', function (){return 'Hello';}, 'post.show');

        $uri = $this->router->generateUri(
            'post.show',
            ['slug' => 'mon-article', 'id' => 18],
            ['p' => 2]
        );
        $this->assertEquals('/blog/mon-article-18?p=2', $uri);
    }
}
