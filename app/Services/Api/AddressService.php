<?php

namespace App\Services\Api;

use App\Repositories\Api\AddressRepository;

class AddressService
{
    protected $addressRepository;
    // constructor
    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    // get countries
    public function getCountries()
    {
        return $this->addressRepository->getCountries();
    }

    // get governorates
    public function getGovernorates($country_id)
    {
        return $this->addressRepository->getGovernorates($country_id);
    }

    //get cities
    public function getCities($governorate_id)
    {
        return $this->addressRepository->getCities($governorate_id);
    }
}
