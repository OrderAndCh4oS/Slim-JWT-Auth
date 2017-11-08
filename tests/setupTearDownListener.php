<?php

use PHPUnit\Framework\TestListenerDefaultImplementation;

class setupTearDownListener implements \PHPUnit\Framework\TestListener
{
    use TestListenerDefaultImplementation;

    public function startTestSuite(\PHPUnit\Framework\TestSuite $suite)
    {
        exec('./vendor/bin/doctrine orm:schema-tool:drop --force');
        exec('./vendor/bin/doctrine orm:schema-tool:create');
    }

    public function endTestSuite(\PHPUnit\Framework\TestSuite $suite)
    {
    }

}
