<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu Giao</title>
</head>
<style>
    body {
  margin: 0;
  padding: 0;
  /* background-color: #FAFAFA; */
  font: 11pt "Tahoma";
  font-family: Verdana, Arial, Helvetica, sans-serif;

}

* {
  box-sizing: border-box;
  -moz-box-sizing: border-box;
}

.page {
  width: 21cm;
  min-height: 29.7cm;
  padding: 0.5cm;
  margin: 0.5cm auto;
  border: 1px #D3D3D3 solid;
  border-radius: 1px;
  background: white;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

.subpage {
  padding: 0.5cm;
  border: 1px white solid;
  height: 290mm;
  outline: 1mm #FAFAFA solid;
}

@page {
  size: A4;
  margin: 0;
}

@media print {
  .page {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    margin: 0;
    border: initial;
    border-radius: initial;
    width: initial;
    min-height: initial;
    box-shadow: initial;
    background: initial;
    page-break-after: always;

  }


 }
</style>

<style>
     .title {
            text-align: center;
            position: relative;
            color: #0000FF;
            font-size: 24px;
            top: 1px;
        }
        .TableData {
            /* background: #ffffff; */
            font: 11px;
            width: 100%;
            border-collapse: collapse;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 11px;
            border: thin solid #d3d3d3;
        }
        .TableData TH {
            background: rgba(0, 0, 255, 0.1);
            text-align: center;
            font-weight: bold;
            color: #000;
            border: solid 1px #ccc;
            height: 24px;
        }

        .TableData TR {
            height: 24px;
            border: thin solid #d3d3d3;
        }

        .TableData TR TD {
            padding-right: 2px;
            padding-left: 2px;
            border: thin solid #d3d3d3;
        }

        .footer-left {
            text-align: center;
            /* text-transform: uppercase; */
            /* padding-top: 24px; */
            position: relative;
            /* height: 150px; */
            width: 30%;
            color: #000;
            float: left;
            /* font-size: 12px; */
            bottom: 1px;
        }

        .footer-right {
            text-align: center;
            /* text-transform: uppercase; */
            /* padding-top: 24px; */
            position: relative;
            /* height: 150px; */
            width: 40%;
            color: #000;
            /* font-size: 12px; */
            float: right;
            bottom: 1px;

        }
        .title-date{
            font-weight: bold;
            font-style: italic ;


        }

</style>

<body onload="window.print();">
    @foreach ($list_all as $key=>$list_so)

        <?php

        $k = 0;
        $page_count = 1;
        $tongcot = 4;
        $page_row = 26;
        $total = count($list_so);

        if ($total % $tongcot == 0) {
            $lines = $total / $tongcot ;
        }else{
            $lines = $total / $tongcot ;
            $lines = round($lines, 0, PHP_ROUND_HALF_DOWN);
            $lines++;

        }

        if ($lines % $page_row == 0) {
            $page_total = $lines / $page_row;
        } else {
            $page_total = ($lines / $page_row) ;
            $page_total = round($page_total, 0, PHP_ROUND_HALF_DOWN);
            $page_total++;

        }

        ?>
            @for ($p = 1; $p <= $page_total ; $p++)

            <div >

        <div class="page">
        <div class="subpage">
        Trang : {{$page_count++}}/{{$page_total}}
        <div class="title"> BIÊN BẢN GIAO HÀNG <br />
            </div> <?php
                $year = getdate()['year'];
                ?> <p style="text-align: center;font-size: 10pt;font-style:italic;">Ngày ...... tháng.......năm
            {{$year}}</p>
            <br />
            <p>Bên giao: <span style="font-weight:bold;">Cty TNHH MTV TM DV Tân Lực Miền Nam</span></p>
            <p>Bên nhận: <span style="font-weight:bold;">..................................................................................</span></p>

            <table class="TableData">
            <tr>
                <th style="text-align: center;">STT</th>
                <th>Mã đơn hàng</th>
                <th style="text-align: center;">STT</th>
                <th>Mã đơn hàng</th>
                <th style="text-align: center;">STT</th>
                <th>Mã đơn hàng</th>
                <th style="text-align: center;">STT</th>
                <th>Mã đơn hàng</th>
            </tr>
                @for ($i = 0; $i < $lines ; $i++)
                <tr>
                    @for ($j = 0; $j < $tongcot; $j++)
                    <?php
                        $k = (($p - 1 ) * $tongcot * $page_row )  //xử lý trang trước trang hiện tại
                                +  $i * $tongcot
                                + $j;
                    ?>
                    <td style="text-align: center;">
                    @if($k <= $total -1)
                        {{$k+1}}
                    @endif
                    </td>
                    <td style="text-align: center;">
                        @if($k <= $total -1)
                        {{$list_so[$k]->madonhangsan}}
                        @endif
                    </td>
                    @endfor
                </tr>
                 @if ($i == ( 26 - 1 ))
                    @break
                @endif

            @endfor
        </table>
        <p style="margin-left: 30pt;">Lưu ý:</p>
        <p style="font-size:12px;font-weight:bold"><i >Tình trạng hàng hóa được đóng kín, không hở, không biến dạng. Vui lòng kiểm tra khi nhận hàng.</i></p>
        <div class="footer-left"> <br>Đại diện bên giao<br /></div>
        <div class="footer-right"> Tổng cộng: ..<span style="font-weight: bolder;">{{$total}}</span>.. Đơn hàng<br>
            Đại diện bên nhận<br />
        </div>
        </div>

        </div>

        </div>

    @endfor


@endforeach
</body>
</html>