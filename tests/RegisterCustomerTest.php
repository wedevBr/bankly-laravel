<?php

namespace WeDevBr\Bankly\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use TypeError;
use WeDevBr\Bankly\Bankly;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Inputs\Customer;
use WeDevBr\Bankly\Inputs\CustomerAddress;
use WeDevBr\Bankly\Inputs\CustomerPhone;

/**
 * RegisterCustomerTest class
 *
 * PHP version 7.4|8.0
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Rafael Teixeira <rafaeldemeirateixeira@gmail.com>
 * @copyright 2020 We Dev Tecnologia Ltda
 * @link      https://github.com/wedevBr/bankly-laravel
 */
class RegisterCustomerTest extends TestCase
{
    use WithFaker;

    protected function getPackageProviders($app)
    {
        return [BanklyServiceProvider::class];
    }

    /**
     * @return array
     */
    public function getFakerHttp(string $path)
    {
        return [
            config('bankly')['login_url'] => Http::response([
                'access_token' => $this->faker->uuid,
                'expires_in' => 3600
            ], 200),
            config('bankly')['api_url'] . "{$path}" => Http::response([], 202)
        ];
    }

    /**
     * @return void
     */
    public function testSuccessRegisterCustomer()
    {
        $client = new Bankly();

        $customerPhone = new CustomerPhone();
        $customerPhone->setCountryCode('55')
            ->setNumber('27999999999');

        $customerAddress = new CustomerAddress();
        $customerAddress->setZipCode('29000000')
            ->setAddressLine('STREET')
            ->setBuildingNumber('100')
            ->setComplement('APT 222')
            ->setNeighborhood('CENTER')
            ->setCity('CITY')
            ->setState('STATE')
            ->setCountry('BR');

        $customer = new Customer();
        $customer->setPhone($customerPhone)
            ->setAddress($customerAddress)
            ->setRegisterName('JHON RAS')
            ->setSocialName('JOJO')
            ->setBirthDate('0000-00-00')
            ->setMotherName('SARA RAS')
            ->setEmail('jonh-ras@test.com');

        $nifNumber = '00000000000000';

        Http::fake($this->getFakerHttp("/customers/{$nifNumber}"));

        $client->customer($nifNumber, $customer);

        Http::assertSent(function ($request) {
            $body = collect($request->data());

            if (array_key_exists('grant_type', $body->toArray())) {
                return true;
            }

            $phone = $body['phone'];
            $address = $body['address'];

            return $phone['countryCode'] === '55'
                && $phone['number'] === '27999999999'
                && $address['country'] === 'BR'
                && $address['state'] === 'STATE'
                && $address['city'] === 'CITY'
                && $address['neighborhood'] === 'CENTER'
                && $address['complement'] === 'APT 222'
                && $address['buildingNumber'] === '100'
                && $address['addressLine'] === 'STREET'
                && $address['zipCode'] === '29000000'
                && $body['socialName'] === 'JOJO'
                && $body['registerName'] === 'JHON RAS'
                && $body['birthDate'] === '0000-00-00'
                && $body['motherName'] === 'SARA RAS'
                && $body['email'] === 'jonh-ras@test.com';
        });
    }

    /**
     * @return void
     */
    public function testReturnTypeErrorIfIncompatibleCustomerData()
    {
        $client = new Bankly();
        $nifNumber = '00000000000000';
        $customer = new \stdClass();

        $this->expectException(TypeError::class);
        $client->customer($nifNumber, $customer);
    }
}
