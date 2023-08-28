<?php
namespace Currency\Helpers;

final class ISOCOde
{
    private $currencyCodesFile = '/../data/currency-codes.csv';
    private $currencyHashMap = [];

    private $existing = [];

    public function __construct()
    {
       $this->readCSV();
    }

    public function exists(string $code): bool
    {
        if (in_array($code, $this->existing))
        {
            return true;
        }

        if (isset($this->currencyHashMap[$code]))
        {
            $this->existing[] = $code;

            return true;
        }

        return false;
    }

    private function readCSV()
    {
        $currency_codes_csv = file(dirname(__FILE__) . $this->currencyCodesFile);

        foreach ($currency_codes_csv as $line) 
        {
            $code = str_getcsv($line)[2] ?? '';

            if (empty($code))
            {
                continue;
            }

            $this->currencyHashMap[$code] = $code;
        }
    }
}