<?php

namespace Tests\Feature;

use Tests\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testMainRequest()
    {
        $response = $this->get('/api/currencies');
        $response->assertOk();
        $response->assertSee('Valute', $escaped = true);
    }

    public function testCreateArrayCurrency()
    {
        $response = $this->get('/api/currencies?q=eurrub,eurusd,usdeur,usdrub,rubusd,rubeur');
        $response->assertOk();
        $response->assertJsonStructure(['EUR' => ['RUB', 'USD'], 'USD' => ['EUR', 'RUB'], 'RUB' => ['USD', 'EUR']]);
    }
}
