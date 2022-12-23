<?php

namespace MiniErp\Domain\Repository;

use MiniErp\Domain\Common\PhoneCallable;

interface PhonesRepository 
{
  public function getPhonesFor(PhoneCallable $phoneCallableObj);
  public function save(PhoneCallable $phoneCallableObj): void;
  public function remove(PhoneCallable $phoneCallableObj): void;
}
