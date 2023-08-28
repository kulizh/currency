<?php
namespace Currency\Market;

interface IMarket
{
   public function getRate(string $from, string $to): float;
}