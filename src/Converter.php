<?php
namespace Currency;

use Exception;
use Currency\Helpers\ISOCode;
use Currency\Market\IMarket;

final class Converter
{
    protected $from = 'THB'; // Thai Baht
    protected $to = 'RUB'; // Russian rouble

    private $isoCodeHandler;
    
    private $market;

    public function __construct()
    {
        $this->isoCodeHandler = new ISOCode();
    }

    public function from(string $from)
    {
        $this->from = $from;

        if (!$this->isoCodeHandler->exists($from))
        {
            throw new \Exception('Currency code ' . $from . ' does not exists');
        }

        return $this;
    }

    public function to(string $to)
    {
        $this->to = $to;

        if (!$this->isoCodeHandler->exists($to))
        {
            throw new \Exception('Currency code ' . $to . ' does not exists');
        }

        return $this;
    }

    public function useMarket(IMarket $market)
    {
        $this->market = $market;
    }

    public function marketFactory(string $name): IMarket
    {
        $classname = __NAMESPACE__ . '\Market\Preset\\' . ucfirst($name);

        if (class_exists($classname))
        {
            return new $classname;
        }

        throw new Exception('Class ' . $name . ' not found');
    }

    public function convert(float $value): float
    {
        if ($this->from === $this->to)
        {
            return $value;
        }

        $currency_rate = $this->market->getRate($this->from, $this->to);

        return $currency_rate * $value;
    }
}