<?php

namespace WeDevBr\Bankly\Traits\Core;

trait AccountLimit
{
    /**
     * Get limits by feature
     *
     * @param string $documentNumber
     * @param string $limitType
     * @param string $featureName
     * @return array
     */
    public function getFeatureLimits(string $documentNumber, string $limitType, string $featureName)
    {
        return $this->get('/holders/' . $documentNumber . '/limits/' . $limitType . '/features/' . $featureName);
    }

    /**
     * Update customer limits by feature
     *
     * @param string $documentNumber
     * @param array|mixed $data
     * @return array
     */
    public function updateCustomerLimits(string $documentNumber, array $data)
    {
        return $this->put('/holders/' . $documentNumber . '/max-limits', $data);
    }
}
