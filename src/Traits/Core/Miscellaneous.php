<?php

namespace WeDevBr\Bankly\Traits\Core;

/**
 * Miscellaneous class
 */
trait Miscellaneous
{
    /**
     * Get a list of banks by products
     * @param string $product
     * @return array|mixed
     * @see https://docs.bankly.com.br/docs/listagem-bancos
     */
    public function getBankList(string $product = 'None')
    {
        return $this->get('/banklist', [
            'product' => $product
        ]);
    }
}
