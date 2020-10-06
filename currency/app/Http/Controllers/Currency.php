<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Currency extends Controller
{
    function index(Request $request): array
    {
        $strPair = $request->get('q');
        $arPair = [];
        if (!!$strPair) {
            $arPair = explode(',', $strPair);
            return $this->getPair($arPair);
        }
        return $this->getCurrencies();
    }

    private function getPair($arPair): array
    {
        $result = [];
        $getCurrency = Cache::remember(
            'currency',
            86400,
            function () {
                if (!empty($this->getCurrencies())) {
                    return $this->getCurrencies();
                }
            }
        );
        if (!$getCurrency) {
            return $result = ['error' => 'Resource not available'];
        }
        $arCurrency = $getCurrency['Valute'];
        foreach ($arPair as $pair) {
            $from = strtoupper(substr($pair, 0, 3));
            $to = strtoupper(substr($pair, -3));
            if ($from === 'RUB') {
                $course = 1 / $arCurrency[$to]['Value'];
            }
            if ($to === 'RUB') {
                $course = $arCurrency[$from]['Value'];
            }
            if (!in_array('RUB', [$from, $to])) {
                $course = $arCurrency[$from]['Value'] / $arCurrency[$to]['Value'];
            }
            $result[$from][$to] = round($course ?? 1, 3);
        }
        return $result;
    }

    private function getCurrencies(): array
    {
        $response = Http::get('https://www.cbr-xml-daily.ru/daily_json.js');
        if ($response->successful()) {
            return $response->json();
        }
        return [];
    }
}
