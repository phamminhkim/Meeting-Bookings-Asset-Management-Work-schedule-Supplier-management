<?php
namespace App\Ultils;

class ReadCurrency
{

    protected $number;
    protected $currency;
    protected $languge;
    protected $unit;
    protected $odd;
    protected $and;
    protected $twounit;
    protected $dictionary = array(
        0 => 'không',
        1 => 'một',
        2 => 'hai',
        3 => 'ba',
        4 => 'bốn',
        5 => 'năm',
        6 => 'sáu',
        7 => 'bảy',
        8 => 'tám',
        9 => 'chín',
        10 => 'mười',
        11 => 'mười một',
        12 => 'mười hai',
        13 => 'mười ba',
        14 => 'mười bốn',
        15 => 'mười lăm',
        16 => 'mười sáu',
        17 => 'mười bảy',
        18 => 'mười tám',
        19 => 'mười chín',
        20 => 'hai mươi',
        30 => 'ba mươi',
        40 => 'bốn mươi',
        50 => 'năm mươi',
        60 => 'sáu mươi',
        70 => 'bảy mươi',
        80 => 'tám mươi',
        90 => 'chín mươi',
        100 => 'trăm',
        1000 => 'nghìn',
        1000000 => 'triệu',
        1000000000 => 'tỷ',
        1000000000000 => 'nghìn tỷ',
        1000000000000000 => 'triệu tỷ',
        1000000000000000000 => 'tỷ tỷ',
    );
    public function __construct($number, $currency = "VND", $languge = "VN", $unit = "đồng", $odd = "", $and = "", $twounit = "")
    {
        $this->number = $number;
        $this->currency = $currency;
        $this->languge = $languge;
        $this->unit = $unit;
        $this->odd = $odd;
        $this->and = $and;
        $this->twounit = $twounit;
    }
    public function read_to_words()
    {

        if ($this->number == 0) {

            $str = $this->dictionary[$this->number] . ' ' . $this->unit;

        } else
        if ($this->twounit === '') { //Đọc 1 đơn vị
            $str = $this->convert_number_to_words($this->number) . ' ' . $this->unit;
        } else { //Đọc 2 đơn vị như tiền đô

            $fraction = $this->get_fraction($this->number);
            if ($fraction) {

                $fraction = ' ' . $this->and . ' ' . $this->convert_number_to_usd((int) $fraction) . ' ' . $this->odd;

            }
            $str = $this->convert_number_to_usd($this->number) . ' ' . $this->unit . $fraction;
        }

        //    switch ($this->currency) {
        //        case 'USD':
        //            $fraction = $this->get_fraction($this->number) ;
        //            if($fraction){
        //                // if($fraction < 10)
        //                // {
        //                //      $xu = (int)$fraction;
        //                //     // dd($this->dictionary[$xu] );
        //                //      $fraction = ' và ' .  $this->dictionary[$xu] . ' xu';
        //                // }else{
        //                //      $fraction = ' và ' .  $this->convert_number_to_usd( $fraction) . ' xu';
        //                // }
        //                $fraction = ' và ' .  $this->convert_number_to_usd((int) $fraction) . ' xu';

        //            }
        //            $str = $this->convert_number_to_usd($this->number) .' đô la Mỹ' . $fraction;
        //            break;

        //        default:
        //            $str = $this->convert_number_to_words($this->number) . ' đồng';
        //            break;

        //    }

        return ucfirst(str_replace("  "," ",$str));
    }
    //Lấy phần sau dấu chấm thập phân
    protected function get_fraction($number)
    {
        $string = $fraction = null;
        if (strpos($number, '.')) {
            list($number, $fraction) = explode('.', $number);
        }
        if ($fraction != null && is_numeric($fraction)) {

            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $this->dictionary[$number];
            }
            // $string .= $usd; //đo la mỹ
            // $string .= $decimal;
            $string .= implode(' ', $words);
            // $string .= $cent;

        }
        return $fraction;
    }
    protected function convert_number_to_usd($number)
    {
        $hyphen = ' '; //gạch nối
        $conjunction = ' lẻ '; //kết hợp
        $separator = ' '; //phân tách
        $negative = 'âm '; //
        $decimal = "và "; //
        $cent = " xu";
        $usd = " đô la Mỹ ";

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.')) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $this->dictionary[$number];

                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = "";
                if ($tens < 10) {
                    $string = ' lẻ ' . $this->dictionary[$tens];
                } else {
                    $string = $this->dictionary[$tens];
                }

                if ($units) {
                    if ($units == 5) {
                        $string .= $hyphen . "lăm";
                    } elseif ($units == 1) {
                        $string .= $hyphen . "mốt";
                    } else {
                        $string .= $hyphen . $this->dictionary[$units];
                    }

                }
                // dd( $string );
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $this->dictionary[$hundreds] . ' ' . $this->dictionary[100];
                if ($remainder) {
                    // $string .= $conjunction . $this->convert_number_to_words($remainder);
                    if ($remainder < 10) {
                        $string .= ' lẻ ' . $this->convert_number_to_words($remainder);

                    } else {
                        $string .= $conjunction . $this->convert_number_to_words($remainder);
                    }

                }
                // dd( $remainder );
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $this->dictionary[$baseUnit];
                //dd( $baseUnit );
                if ($remainder) {
                    $string .= $remainder < 100 ? " không trăm " : $separator;
                    //$string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }

                break;
        }

        return $string;

    }
    public function convert_number_to_words($number)
    {
        $hyphen = ' '; //gạch nối
        $conjunction = ' '; //kết hợp
        $separator = ' '; //phân tách
        $negative = 'âm '; //
        $decimal = ' phẩy '; //

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.')) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                // dd("ssss".$number);

                if ($number == '012') {
                    // dd("ddd1==". $number);
                    //dd( $numBaseUnits );
                }
                $num = (int) $number;

                if (strlen($number) == 3 && $num < 10 && $num > 0) {
                    $string = 'không trăm lẻ ' . $this->dictionary[$num];
                    //dd("dung");
                }elseif(strlen($number) == 3 && $num > 10 && $num < 100){
                    $string = 'không trăm ' . $this->dictionary[$num];
                }elseif (strlen($number) == 2 && $num < 10 && $num > 0) {
                    $string = 'lẻ' . $this->dictionary[$num];
                    //dd("dung");
                } elseif ($num > 0) {
                    $string = $this->dictionary[(int) $number];
                }
                break;
            case $number < 100:

                $tens = ((int) ($number / 10)) * 10;
                // $tens = ($number / 10) * 10;
                $units = $number % 10;
                // dd("dung=".$tens);
                $zero_len = strlen($number);
                //dd($zero_len);
                if ($zero_len > 0) {
                    $zero = "";
                    for ($i = 0; $i < $zero_len; $i++) {
                        $zero = '0' . $zero;
                    }
                    // $numBaseUnits = $zero . $numBaseUnits;
                    //$remainder = $zero . $remainder;
                    //dd($numBaseUnits);
                }
                // if ($number == '0') {
                //     //dd("ddd=". $number);

                // }
                // dd("ssss");
                if ($zero_len == 3 && $tens < 100) {
                    $string = 'không trăm ' . $this->dictionary[$tens];
                } elseif ($zero_len == 3 && $tens < 10) {
                    $string = 'không trăm lẻ ' . $this->dictionary[$tens];
                } else {
                    // dd($tens);
                    $string = $this->dictionary[$tens];
                }

                //dd($units);

                if ($units) {
                    if ($units == 5) {
                        $string .= $hyphen . "lăm";
                    } elseif ($units == 1) {
                        $string .= $hyphen . "mốt";
                    } else {
                        $string .= $hyphen . $this->dictionary[$units];
                    }

                }

                // dd( $string );
                break;
            case $number < 1000:

                $hundreds = ($number / 100);
                $remainder = $number % 100;
                //("<1000". $number);

                $string = $this->dictionary[$hundreds] . ' ' . $this->dictionary[100];
                //dd($remainder);
                if ($remainder) {
                    if ($remainder < 10) {
                        $string .= ' lẻ ' . $this->dictionary[$remainder]; // $this->convert_number_to_words($remainder);

                    } else {
                        // $string .= $conjunction . $this->dictionary[$remainder];;
                        //dd($remainder);
                        $string .= $conjunction . $this->convert_number_to_words($remainder);
                    }

                }
                // dd( $remainder );
                break;
            default:

                $baseUnit = pow(1000, floor(log($number, 1000)));
                // $numBaseUnits = (int) ($number / $baseUnit);
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $zero_len = strlen($number) - strlen((int) $baseUnit) - strlen($numBaseUnits) + 1;
                if ($zero_len > 0) {
                    $zero = "";
                    for ($i = 0; $i < $zero_len; $i++) {
                        $zero = '0' . $zero;
                    }
                    $numBaseUnits = $zero . $numBaseUnits;
                    //$remainder = $zero . $remainder;
                    //dd($numBaseUnits);
                }
                $zero_len = strlen((int) $baseUnit) - strlen($remainder) - 1;
                //dd($zero_len );
                if ($zero_len > 0) {
                    $zero = "";
                    for ($i = 0; $i < $zero_len; $i++) {
                        $zero = '0' . $zero;
                    }
                    $remainder = $zero . $remainder;
                    //$remainder = $zero . $remainder;
                    //dd($numBaseUnits);
                }

                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $this->dictionary[$baseUnit];
                
                // dd( $string );
                if ($remainder) {
                    //  dd($remainder);
                    // if ($number == '005012') {
                    //     dd("ddd=". $remainder);
                    //     //dd( $numBaseUnits );
                    // }
                    // $string .= $remainder < 100 ? " không trăm " : $separator;
                    $string .= ' ' . $this->convert_number_to_words($remainder);
                }

                break;
            }
            if ($fraction != null && is_numeric($fraction)) {
                $string .= $decimal;
                $words = array();
                foreach (str_split((string) $fraction) as $number) {
                    $words[] = $this->dictionary[$number];
                }
                //  $string .= implode(' ', $words);
                $string .= $this->convert_number_to_words((int) $fraction);
                // $string .=  $this->convert_number_to_words($fraction);
            }

        return $string;

    }
}
