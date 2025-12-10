<?php

namespace WeDevBr\Bankly\Tests\Customer;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use WeDevBr\Bankly\BanklyServiceProvider;
use WeDevBr\Bankly\Inputs\Customer;
use WeDevBr\Bankly\Inputs\CustomerAddress;
use WeDevBr\Bankly\Inputs\CustomerPhone;
use WeDevBr\Bankly\Tests\TestCase;

/**
 * UpdateCustomerTest class
 */
class UpdateCustomerTest extends TestCase
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
            config('bankly')['api_url']."{$path}" => Http::response([], 202),
        ];
    }

    public function test_success_update_customer_filters_and_shapes_payload()
    {
        $documentNumber = '12345678909';

        Http::fake($this->getFakerHttp("/customers/{$documentNumber}"));

        $phone = new CustomerPhone;
        $phone->setCountryCode('55')->setNumber('27999999999');

        $address = new CustomerAddress;
        $address->setZipCode('29000000')
            ->setAddressLine('STREET')
            ->setBuildingNumber('100')
            ->setComplement('APT 222')
            ->setNeighborhood('CENTER')
            ->setCity('CITY')
            ->setState('ES')
            ->setCountry('BR');

        $customer = new Customer;
        $customer->setPhone($phone)
            ->setAddress($address)
            ->setSocialName('JOJO')
            ->setRegisterName('JOHN DOE')
            ->setEmail('john.doe@example.com')
            ->setBirthDate('1990-01-01')
            ->setMotherName('JANE DOE')
            ->setAssertedIncome(1234.56)
            ->setCurrencyIncome('BRL')
            ->setPepLevel('NONE')
            ->setHasBrazilianNationality(true)
            ->setSelfieToken('selfie-token')
            ->setIdCardFrontToken('front-token')
            ->setIdCardBackToken('back-token')
            ->setOccupation('OCP0001');

        $client = $this->getBanklyClient();
        $client->updateCustomer($documentNumber, $customer);

        Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($documentNumber) {
            $urlOk = Str::contains($request->url(), "/customers/{$documentNumber}");
            $methodOk = strtoupper($request->method()) === 'PATCH';
            $body = $request->data();

            // Body must have a 'data' key with only allowed, non-null fields
            if (! isset($body['data']) || ! is_array($body['data'])) {
                return false;
            }

            $data = $body['data'];

            $hasEmail = isset($data['email']) && $data['email'] === 'john.doe@example.com';
            $hasSocial = isset($data['socialName']) && $data['socialName'] === 'JOJO';
            $hasPhone = isset($data['phone']) && $data['phone']['countryCode'] === '55' && $data['phone']['number'] === '27999999999';
            $hasAddress = isset($data['address']) && $data['address']['country'] === 'BR' && $data['address']['state'] === 'ES';
            $hasAssertedIncome = isset($data['assertedIncome']) && $data['assertedIncome']['value'] === '1234.56' && $data['assertedIncome']['currency'] === 'BRL';
            $hasPep = isset($data['pep']) && $data['pep']['level'] === 'NONE';
            $hasNationality = isset($data['hasBrazilianNationality']) && $data['hasBrazilianNationality'] === true;
            $hasOccupation = isset($data['occupation']) && $data['occupation'] === 'OCP0001';

            // Ensure disallowed keys are not present
            $noRegisterName = ! array_key_exists('registerName', $data);
            $noDocumentation = ! array_key_exists('documentation', $data);

            return $urlOk && $methodOk &&
                $hasEmail && $hasSocial && $hasPhone && $hasAddress && $hasAssertedIncome && $hasPep && $hasNationality && $hasOccupation &&
                $noRegisterName && $noDocumentation;
        });
    }
}
