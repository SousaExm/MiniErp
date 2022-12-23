<?php

namespace MiniErp\Domain\Repository\Common;

use MiniErp\Domain\Common\PhoneCallable;

interface PhoneRepository 
{
  public function getPhonesFor(PhoneCallable $phoneCallableObj);
  public function save(PhoneCallable $phoneCallableObj): void;
  public function remove(PhoneCallable $phoneCallableObj): void;
}
