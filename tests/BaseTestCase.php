<?php

namespace Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;

/**
 * Class BaseTestCase
 * @package Tests
 */
class BaseTestCase extends TestCase
{

    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * BaseTestCase constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $settings = require __DIR__.'/../src/settings.php';
        $config = Setup::createAnnotationMetadataConfiguration(
            $settings['settings']['doctrine']['meta']['entity_path'],
            $settings['settings']['doctrine']['meta']['auto_generate_proxies'],
            $settings['settings']['doctrine']['meta']['proxy_dir'],
            $settings['settings']['doctrine']['meta']['cache'],
            false
        );
        $this->entityManager = EntityManager::create($settings['settings']['doctrine']['connection'], $config);
    }
}
