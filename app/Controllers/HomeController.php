<?php

namespace App\Controller;

use App\Model\HomeModel;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class HomeController extends Controller
{

    private $hello = 0;
    private $home;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->home = new HomeModel($this->db);
    }

    public function home(Request $request, Response $response)
    {
        $this->logger->addInfo("Ticket list");

        $response->getBody()->write('Hi, I am here' . $this->hello++);
        return $response;
    }
}