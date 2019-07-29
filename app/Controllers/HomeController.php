<?php
namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class HomeController extends Controller
{

    private $hello = 0;


    public function home(Request $request, Response $response)
    {
        $this->logger->addInfo("Ticket list");

        $response->getBody()->write('Hi, I am here' . $this->hello++);
        return $response;
    }
}