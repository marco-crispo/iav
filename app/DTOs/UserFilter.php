<?php

namespace App\DTOs;

/**
 * UserFilter
 */
class UserFilter
{
    public float $latitude;
    public float $longitude;
    public ?array $filters;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        float $latitude,
        float $longitude,
        ?array $filters = null,
    ) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->filters = $filters;
    }
}
