<?php

namespace WeDevBr\Bankly\Validators\Pix;

use InvalidArgumentException;
use WeDevBr\Bankly\Types\Pix\Location;

/**
 * LocationValidator class
 *
 * PHP 8.1|8.2|8.3
 *
 * @author    WeDev Brasil Team <contato@wedev.software>
 * @author    Yan Gonçalves <yanw100@gmail.com>
 * @copyright 2021 We Dev Tecnologia Ltda
 *
 * @link      https://github.com/wedevBr/bankly-laravel/
 */
class LocationValidator
{
    /** @var Location */
    private $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    /**
     * Validate the attributes of the location
     */
    public function validate(): void
    {
        $this->validateZipCode();
        $this->validateCity();
    }

    /**
     * This validates the zip code
     *
     * @throws InvalidArgumentException
     */
    private function validateZipCode(): void
    {
        $zipCode = $this->location->zipCode;
        if (empty($zipCode) || ! is_string($zipCode)) {
            throw new InvalidArgumentException('zip code should be a string');
        }
    }

    /**
     * This validates the city
     *
     * @throws InvalidArgumentException
     */
    private function validateCity(): void
    {
        $city = $this->location->city;
        if (empty($city) || ! is_string($city)) {
            throw new InvalidArgumentException('city should be a string');
        }
    }
}
