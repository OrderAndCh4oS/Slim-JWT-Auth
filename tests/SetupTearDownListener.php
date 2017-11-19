<?php

namespace Tests;

use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

class SetupTearDownListener implements TestListener
{
    use TestListenerDefaultImplementation;

    public function startTestSuite(TestSuite $suite)
    {
        exec('./vendor/bin/doctrine orm:schema-tool:drop --force');
        exec('./vendor/bin/doctrine orm:schema-tool:create');
        echo $suite->getName()."\n";
    }

    public function endTestSuite(TestSuite $suite)
    {
        exec('./vendor/bin/doctrine orm:schema-tool:drop --force');
        echo $suite->getName()." Completed\n";
    }

}
