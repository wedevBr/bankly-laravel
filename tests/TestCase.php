<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as TestbenchTestCase;
use ReflectionException;
use ReflectionProperty;
use WeDevBr\Bankly\Auth\Auth;
use WeDevBr\Bankly\Bankly;

/**
 * TestCase class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel
 */
abstract class TestCase extends TestbenchTestCase
{
    use WithFaker;

    /**
     * @throws ReflectionException
     */
    public function getBanklyClient(): Bankly
    {
        $client = new Bankly();
        $auth = Auth::login();
        $token = new ReflectionProperty($auth, 'token');
        $token->setValue($auth, $this->faker->uuid);

        $tokenExpiry = new ReflectionProperty($auth, 'tokenExpiry');
        $tokenExpiry->setValue($auth, now()->addSeconds(3600)->unix());

        return $client;
    }

    /**
     * @throws ReflectionException
     */
    public function auth(): void
    {
        $auth = Auth::login();
        $token = new ReflectionProperty($auth, 'token');
        $token->setValue($auth, $this->faker->uuid);

        $tokenExpiry = new ReflectionProperty($auth, 'tokenExpiry');
        $tokenExpiry->setValue($auth, now()->addSeconds(3600)->unix());
    }
}
