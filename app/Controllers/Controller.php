<?php
namespace App\Controller;

use Psr\Container\ContainerInterface;

abstract class Controller
{
    protected $container;
    protected $logger;
    protected $db;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->logger = $container->get('logger');
        $this->db = $container->get('db');
    }
}