<?php

namespace MiniErp\Domain\Common;

interface PhonesList
{
  public function addPhone(Phone $phone): void;

  public function removePhone(string $phone): void;

  public function updatePhone(string $oldPhone, Phone $newPhone): void;

  /**
   * @return Phone[]
   */
  public function phones(): array;

   /**
   * @return Phone[]
   */
  public function lastRemovedPhones(): array;
}
