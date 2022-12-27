<?php

namespace MiniErp\Domain\Common;

use MiniErp\Domain\Common\Phone;

interface PhoneCallable
{
  public function uuid(): string | null;

  public function phonesList(): PhonesList;
}