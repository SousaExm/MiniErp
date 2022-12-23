<?php

namespace MiniErp\Domain\Repository;

use MiniErp\Domain\Common\Addressable;

interface AddressRepository
{
  public function getAddressFor(Addressable $addressableObj);
  public function save(Addressable $addressableObj): void;
  public function remove(Addressable $addressableObj): void;
}
