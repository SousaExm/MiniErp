<?php

namespace MiniErp\Domain\Common;

interface Addressable
{
  public function uuid(): string | null;
  public function address(): Address | null;
  public function addAddress(Address $address);
}