<?php

namespace WeDevBr\Bankly\Tests;

use Orchestra\Testbench\TestCase;
use WeDevBr\Bankly\BanklyServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
