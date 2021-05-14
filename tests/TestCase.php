<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as TestbenchTestCase;
use ReflectionProperty;
use WeDevBr\Bankly\Bankly;

/**
 * TestCase class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
abstract class TestCase extends TestbenchTestCase
{
    use WithFaker;

    /**
     * @return Bankly
     */
    public function getBanklyClient()
    {
        $client = new Bankly();
        $token = new ReflectionProperty(Bankly::class, 'token');
        $token->setAccessible(true);
        $token->setValue($client, $this->faker->uuid);

        $tokenExpiry = new ReflectionProperty(Bankly::class, 'token_expiry');
        $tokenExpiry->setAccessible(true);
        $tokenExpiry->setValue($client, now()->addSeconds(3600)->unix());

        return $client;
    }
}
