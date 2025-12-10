<?php

namespace WeDevBr\Bankly;

use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use WeDevBr\Bankly\HttpClients\BaseHttpClient;
use WeDevBr\Bankly\Requests\AutomaticPix\AuthorizeRequest;

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
    public function authorize(string $idRecurrence, string $nifNumber, AuthorizeRequest $request): array
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
}
