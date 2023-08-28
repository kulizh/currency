<?php
namespace Currency\Helpers;

class MarketCache
{
    private static $CACHE_LIFETIME = 86400; // 1 day
    private static $CACHE_DIR = '/../data/market/cache/';

    public static function read(string $url, string $name, bool $force = false): string
    {
        $cache_filename = dirname(__FILE__) . self::$CACHE_DIR . $name . '.json';

        if (
            (!is_readable($cache_filename)
            || filemtime($cache_filename) < (time() - self::$CACHE_LIFETIME)) && !$force
            )
        {
            $data = file_get_contents($url);
     
            file_put_contents($cache_filename, $data);
           
            return $data;
        }

        return file_get_contents($cache_filename);
    }
}