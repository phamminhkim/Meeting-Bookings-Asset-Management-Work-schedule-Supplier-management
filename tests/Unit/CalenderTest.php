<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Ultils\Ultils;
class CalenderTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
      // $kq =   Ultils::check_workday('1000','HC',"2022","11","07");
      $kq =   Ultils::check_ngaylam('1000','HC',"2022","01","03","30");//2022","10","05","10"
                                                      //22true 25
      //  $this->assertEquals($kq['workday'],false);
      $this->assertEquals($kq['check_ngaylam'],true);
       dd($kq);
    }
}
