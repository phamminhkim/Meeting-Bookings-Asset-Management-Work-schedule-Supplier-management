<?php

namespace Tests\Feature\Repositories\shared;

use App\Repositories\shared\FactoryCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FactoryCodeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateCode()
    {
        $response = $this->get('/');
        $factoryCode = new FactoryCode;
        $factoryCode->documentCode = 'DNTT';
        $factoryCode->company_id = '1000';
        $factoryCode->budget_type = '1';
        $factoryCode->payment_type_id = '1';
        $factoryCode->currency = 'VND';
        $factoryCode->name = 'H1';
        $this->assertEquals($factoryCode->create(),"DNTT_1000_1_1_VND_H1");
    }
}
