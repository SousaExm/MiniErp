<?php

namespace MiniErp\Domain\Common;

use MiniErp\Domain\Customer\Phone;

interface PhoneCallable
{
  public function uuid(): string | null;

  /**
   * Summary of phones
   * @return Phone[]
   */
  public function phones(): array;
  public function addPhone(string $areaCode, string $phoneNumber, bool $hasWhatsApp);
}