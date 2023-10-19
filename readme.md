# PHP currency converter
The library converts currencies based on the rates that can be obtained through the API or by parsing the bank's pages.

## Usage
Install package via Composer:
```
composer require kulizh/currency
```

Then include the library in your PHP-file: 
```php
<?php
require 'vendor/autoload.php';

use Currency\Converter;

$converter = new Converter();
```

## Set From and To 
Set _from_ and _to_ via special methods `from(string $isoCode): self` and `to(string $isoCode): self`, where `isoCode` is currency code stored in `data/market/currency-codes.csv`.

```php
$converter->from('RUB')->to('USD');
```

## Rates source
### Use presets
You may use one of preset rate markets. Now available: 
1. Bank of Thai market (`BankOfThai`)

Get the instance of market class via factory:
```php
$thaiMarket = $covnerter->marketFactory('BankOfThai');
```

The market classes implement the `iMarket` interface.

### Create your own
You can use any service, website or public API as a source of exchange rates. To do this, implement the `iMarket` interface. 

```php
<?php
namespace MyMarket;

use Currency\Helpers\MarketCache;
use Currency\Market\IMarket;

class CentralBankOfRussia implements IMarket
{
    /* 
    * Here is the string we parse.
    * This could be API Url or whatever
    **/
    protected string $url = 'https://cb.ru/rates.json';

    /**
     * $from string isoCode of From currency
     * $to string isoCode of To currency
     * 
     * @return float Currency result
     */
    public function getRate(string $from, string $to): float
    {
        // Optional: you can cache data to avoid ban or freeze
        $data = MarketCache::read($this->url, 'cb.rf');

        $data_decoded = json_decode($data, true);

        /*
        * Place your script to get rates FROM or TO here
        **/

        return 0.00;
    }
}

```

__Note:__ Feel free to make PR with your implementations of Market objects. 

### Pass it to Library
Pass the market instance you created through method `useMarket()`:

```php
$myMarket = new MyMarket\CentralBankOfRussia();

$converter->useMarket($myMarket);
```

## Get the result
```php
use Currency\Converter;

$usdPrice = 13670;

$converter = new Converter();
$converter->from('usd')->to('rub');

$rubPrice = $converter->convert($usdPrice);
```