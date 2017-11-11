<?php

namespace Error;

use Oacc\Error\Error;
use PHPUnit\Framework\TestCase;

/**
 * Class ErrorTest
 * @package Error
 */
class ErrorTest extends TestCase
{
    /**
     * @var Error $error
     */
    private $error;

    public function testErrors()
    {
        $this->assertEquals(false, $this->error->hasErrors());
        $this->error->addError('feature', 'broken feature');
        $this->assertEquals(true, $this->error->hasErrors());
        $this->assertEquals(['feature' => ['broken feature']], $this->error->getErrors());
    }

    protected function setUp()
    {
        $this->error = new Error();
    }
}
