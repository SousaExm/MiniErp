<?php

namespace MiniErp\Domain\Common;

interface PhonesList
{
  public function addPhone(string $areaCode, string $phoneNumber, bool $hasWhatsApp): void;

  public function removePhone(string $phone): void;

  public function updatePhone(string $oldPhone, string $areaCode, string $phoneNumber, bool $hasWhatsApp): void;

  /**
   * @return Phone[]
   */
  public function phones(): array;

   /**
   * @return Phone[]
   */
  public function lastRemovedPhones(): array;
}
