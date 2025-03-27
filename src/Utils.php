<?php

namespace App;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Utils
{
    public static function requestPage(string $url, string $baseUrl = '', string $prefix = ''): string
    {
        $client = new Client();

        // If the URL is relative, prepend the base URL and prefix
        if (!empty($baseUrl) && strpos($url, 'http') !== 0) {
            $url = rtrim($baseUrl, '/') . '/' . ltrim($url, '/');
        }
        if (!empty($prefix)) {
            $url = rtrim($prefix, '/') . '/' . ltrim($url, '/');
        }

        // Log the full URL being requested
        error_log('Requesting URL: ' . $url);

        try {
            $response = $client->get($url);
            return $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            // Log the error message
            error_log('HTTP request failed: ' . $e->getMessage());
            throw new \RuntimeException('Unable to fetch the page: ' . $url);
        }
    }

    public static function parseIni(string $domain): array
    {
        $dir = './ini';
        $iniFiles = scandir($dir);

        foreach ($iniFiles as $iniFile) {
            if ($domain . '.ini' !== $iniFile) {
                continue;
            }

            return parse_ini_file($dir . '/' . $iniFile, true);
        }

        throw new \RuntimeException('Unable to parse ini file for domain: ' . $domain . "\n");
    }
}
