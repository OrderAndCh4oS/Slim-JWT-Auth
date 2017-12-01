<?php

namespace Oacc;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use League\Fractal\Manager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Oacc\Controller\AuthController;
use Oacc\Controller\UserController;
use Psr\Container\ContainerInterface;
use Slim\Container;

/**
 * Class Dependencies
 * @package Oacc
 */
class Dependencies
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Dependencies constructor.
     * @param ContainerInterface $container
     * @throws \Doctrine\ORM\ORMException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->container['logger'] = $this->getLogger($container);
        $this->container['em'] = $this->getEntityManager($container);
        $this->container['fractal'] = function () {
            return new Manager();
        };
        $this->container['Oacc\Controller\AuthController'] = function (Container $container) {
            return new AuthController($container);
        };
        $this->container['Oacc\Controller\UserController'] = function (Container $container) {
            return new UserController($container);
        };
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     * @return Logger
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getLogger(ContainerInterface $container): Logger
    {
        $settings = $container->get('settings')['logger'];
        $logger = new Logger($settings['name']);
        $logger->pushProcessor(new UidProcessor());
        $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));

        return $logger;
    }

    /**
     * @param ContainerInterface $container
     * @return EntityManager
     * @throws \Doctrine\ORM\ORMException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getEntityManager(ContainerInterface $container): EntityManager
    {
        $settings = $container->get('settings')['doctrine'];
        $config = Setup::createAnnotationMetadataConfiguration(
            $settings['meta']['entity_path'],
            $settings['meta']['auto_generate_proxies'],
            $settings['meta']['proxy_dir'],
            $settings['meta']['cache'],
            false
        );
        $entityManager = EntityManager::create($settings['connection'], $config);

        return $entityManager;
    }
}
