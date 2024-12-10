<?php
declare(strict_types=1);

use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Router\Annotation\Route;

class HomeController
{
    /**
     * Read more about Prototyping:.
     *
     * @link https://spiral.dev/docs/basics-prototype
     */
    use PrototypeTrait;

    #[Route(route: '/', name: 'home')]
    public function index(): string
    {
        trap('home');
        return $this->views->render('home.twig');
    }

    /**
     * Example of exception page.
     */
    #[Route(route: '/exception', name: 'exception')]
    public function exception(): never
    {
        throw new Exception('This is a test exception.');
    }

}
