<?php
namespace Currency\Market\Preset;

use Currency\Helpers\MarketCache;
use Currency\Market\IMarket;

/**
 * Bank of Thai {https://www.bot.or.th/}
 * 
 */
class BankOfThai implements IMarket
{
    protected $url = 'https://www.bot.or.th/content/bot/en/statistics/exchange-rate/jcr:content/root/container/statisticstable2.results.level3cache.json';

    public function getRate(string $from, string $to): float
    {
        $data = MarketCache::read($this->url, 'bank-of-thai');

        $data_decoded = json_decode($data, true);
        
        if (empty($data_decoded['responseContent']))
        {
            // todo: Force attempt
            return 0.00;
        }

        foreach ($data_decoded['responseContent'] as $item)
        {
            if ($item['currency_id'] === $to)
            {
                return floatval(1 / $item['selling']);
            }
        }

        return 0.00;
    }
}