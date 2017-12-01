<?php

namespace Tests;

use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

/**
 * Class SetupTearDownListener
 * @package Tests
 */
class SetupTearDownListener implements TestListener
{
    use TestListenerDefaultImplementation;

    /**
     * @param TestSuite $suite
     */
    public function startTestSuite(TestSuite $suite)
    {
        exec('./vendor/bin/doctrine orm:schema-tool:drop --force');
        exec('./vendor/bin/doctrine orm:schema-tool:create');
        echo $suite->getName()."\n";
    }

    /**
     * @param TestSuite $suite
     */
    public function endTestSuite(TestSuite $suite)
    {
        exec('./vendor/bin/doctrine orm:schema-tool:drop --force');
        exec('./vendor/bin/doctrine orm:schema-tool:create');
        echo $suite->getName()." Completed\n";
    }

}
