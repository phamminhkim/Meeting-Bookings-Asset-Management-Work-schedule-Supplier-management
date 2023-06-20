<?php

namespace Tests\Unit;

use App\Ultils\ReadCurrency;
use PHPUnit\Framework\TestCase;

class ReadCurrencyTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
    public function test_read_to_words_10005012(){
        //VND
        $num = "10005012";
        $word = "Mười triệu không trăm lẻ năm nghìn không trăm mười hai đồng";
        $read = new ReadCurrency($num,'VND','VN','đồng','','','');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);

        $num = "102021005";
        $word = "Một trăm lẻ hai triệu không trăm hai mươi mốt nghìn không trăm lẻ năm đồng";
        $read = new ReadCurrency($num,'VND','VN','đồng','','','');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);

        $num = "102021005";
        $word = "Một trăm lẻ hai triệu không trăm hai mươi mốt nghìn không trăm lẻ năm đồng";
        $read = new ReadCurrency($num,'VND','VN','đồng','','','');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);

        $num = "105006007";
        $word = "Một trăm lẻ năm triệu không trăm lẻ sáu nghìn không trăm lẻ bảy đồng";
        $read = new ReadCurrency($num,'VND','VN','đồng','','','');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);
        
        $num = "204005";
        $word = "Hai trăm lẻ bốn nghìn không trăm lẻ năm đồng";
        $read = new ReadCurrency($num,'VND','VN','đồng','','','');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);
        
        $num = "100000.23";
        $word = "Một trăm nghìn phẩy hai mươi ba đồng";
        $read = new ReadCurrency($num,'VND','VN','đồng','','','');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);
        
        $num = "4545";
        $word = "Bốn nghìn năm trăm bốn mươi lăm đồng";
        $read = new ReadCurrency($num,'VND','VN','đồng','','','');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);

        $num = "4343434";
        $word = "Bốn triệu ba trăm bốn mươi ba nghìn bốn trăm ba mươi bốn đồng";
        $read = new ReadCurrency($num,'VND','VN','đồng','','','');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);



        $num = "10000000";
        $word = "Mười triệu đồng";
        $read = new ReadCurrency($num,'VND','VN','đồng','','','');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);

        $num = "700000000";
        $word = "Bảy trăm triệu đồng";
        $read = new ReadCurrency($num,'VND','VN','đồng','','','');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);
        

        //Yên Nhật
        $num = "60926.55";
        $word = "Sáu mươi nghìn chín trăm hai mươi sáu phẩy năm mươi lăm Yên Nhật";
        $read = new ReadCurrency($num,'JPY','VN','Yên Nhật','','','');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);

        $num = "7021000";
        $word = "Bảy triệu không trăm hai mươi mốt nghìn Yên Nhật";////"Bảy triệu không trăm hai mươi mốt nghìn Yên Nhật";
                //  Bảy triệu không trăm hai mươi mốt nghìn Yên Nhật
        $read = new ReadCurrency($num,'JPY','VN','Yên Nhật','','','');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);
        
        
        //USD
        $num = "5";
        $word = "Năm đô la Mỹ";
        $read = new ReadCurrency($num,'USD','VN','đô la Mỹ','xu','và','X');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);

        $num = "102035.15";
        $word = "Một trăm lẻ hai nghìn không trăm ba mươi lăm đô la Mỹ và mười lăm xu";
        $read = new ReadCurrency($num,'USD','VN','đô la Mỹ','xu','và','X');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);

        $num = "1222.35";
        $word = "Một nghìn hai trăm hai mươi hai đô la Mỹ và ba mươi lăm xu";
        $read = new ReadCurrency($num,'USD','VN','đô la Mỹ','xu','và','X');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);

        $num = "113917.26";
        $word = "Một trăm mười ba nghìn chín trăm mười bảy đô la Mỹ và hai mươi sáu xu";
        $read = new ReadCurrency($num,'USD','VN','đô la Mỹ','xu','và','X');
        $re = $read->read_to_words();
        print("\r\n" . $num .':');
        print("\r\n Đọc:" . $re );
        print("\r\n KQ :" . $word );
        $this->assertTrue($re == $word);
        
        

        
    }
}
