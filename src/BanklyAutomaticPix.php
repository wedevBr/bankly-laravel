<?php

namespace WeDevBr\Bankly;

use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Support\Contracts\AuthorizeRequestInterface;
use WeDevBr\Bankly\Support\Contracts\AutomaticPixAuthorizationEditInterface;

class BanklyAutomaticPix extends BaseHttpClient
{
    public const GET_AUTHORIZATIONS_ENDPOINT = '/pix/recurring-payments';

    public const AUTHORIZE_ENDPOINT = '/pix/recurring-payments/{idRecurrence}/confirm';

    public const CANCEL_ENDPOINT = '/pix/recurring-payments/{idRecurrence}';

    /**
     * @throws RequestException
     */
    public function getAuthorizations(
        string $nifNumber,
        ?Carbon $initialDate = null,
        ?Carbon $finalDate = null,
        ?string $idRecurrence = null,
        ?string $contractNumber = null,
        ?Enums\Pix\AutomaticPix\StatusEnum $status = null,
        ?int $page = 1,
        ?int $pageSize = 100,
    ): array {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this->get(self::GET_AUTHORIZATIONS_ENDPOINT,
            array_filter([
                'initialDate' => $initialDate,
                'finalDate' => $finalDate,
                'idRecurrence' => $idRecurrence,
                'contractNumber' => $contractNumber,
                'status' => $status?->value,
                'page' => $page,
                'pageSize' => $pageSize,
            ])
        );
    }

    /**
     * @throws RequestException
     */
    public function authorize(string $idRecurrence, string $nifNumber, AuthorizeRequestInterface $request): array
    {
        $endpoint = str_replace('{idRecurrence}', $idRecurrence, self::AUTHORIZE_ENDPOINT);

        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this->patch($endpoint, $request->toArray());
    }

    /**
     * @throws RequestException
     */
    public function cancel(string $idRecurrence, string $nifNumber): array
    {
        $endpoint = str_replace('{idRecurrence}', $idRecurrence, self::CANCEL_ENDPOINT);

        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this->delete($endpoint);
    }

    /**
     * Edit the max value of a Pix Automático authorization.
     *
     * @param  string  $nifNumber  User document number.
     * @param  AutomaticPixAuthorizationEditInterface  $authorization  The authorization update data.
     *
     * @throws RequestException
     */
    public function editAuthorization(string $nifNumber, AutomaticPixAuthorizationEditInterface $authorization): array
    {
        $this->setHeaders([
            'x-bkly-pix-user-id' => $nifNumber,
        ]);

        return $this->put('/pix/automatic/authorization', $authorization->toArray(), null, true);
    }
}
