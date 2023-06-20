<?php

namespace App\Ultils\Pdf;

use setasign\Fpdi;

/**
 * Thư viện mở rộng của FPDI & TFPDF
 * - Hỗ trợ encoding cho UTF-8
 * - Xử lí file PDF theo template, giúp dễ dàng định dạng và xử lí dữ liệu
 * - "setasign/fpdf": "^1.8",
 * - "setasign/fpdi": "^2.0",
 * - "setasign/tfpdf": "^1.32"
 * @package   setasign/Fpdi
 * @package   setasign/fpdf
 * @package   setasign/tfpdf
 * @copyright Copyright (c) 2020 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */

class ThienLongPDF extends Fpdi\Tfpdf\Fpdi
{
  private $pageCount = 0;
  private $pageInfo = [];
  private $fields = [];
  private $datas = [];
  private $defaultFont = '';
  private $defaultFontSize = 12;

  /**
   * Tải template của file PDF mẫu
   * @param string $templateFilePath Đường dẫn đến template *.pdf
   */
  public function LoadTemplate($templateFilePath)
  {
    $this->AutoPageBreak = false;
    $this->pageCount = $this->setSourceFile($templateFilePath);
    $this->SetCreator("TLPDF");
  }

  /**
   * Tải font sẽ sử dụng trong template
   * @param string $fontName Tên đầy đủ của font
   * @param string $fontStyle Kiểu chữ ('R'=Regular, 'B'=Bold, 'I'=Italic, 'BI'=BoldItalic)
   * @param string $fontFile Đường dẫn đến font *.ttf
   */
  public function LoadFont($fontName, $fontStyle, $fontFile)
  {
    $this->AddFont($fontName, $fontStyle, $fontFile, true);
  }

  /**
   * Lấy thông số của trang (hướng trang, độ dài độ rộng theo mm), để trống sẽ trả về list
   * - Lưu ý: Phải chạy hàm RenderPDFData trước khi sử dụng
   * @param string $pageNumber Số trang (tính từ 1)
   * @return array orientation, width, height
   */
  public function GetPageInfo($pageNumber = 0)
  {
    if ($pageNumber == 0)
      return $this->pageInfo;
    return $this->pageInfo[$pageNumber] ?? '';
  }

  /**
   * Chọn font mặc định khi sử dụng hàm CreateTextField
   * - Lưu ý: Chỉ có thể dùng font đã được load bởi hàm LoadFont
   * @param string $fontName Tên đầy đủ của font
   */
  public function SetDefaultFont($fontName)
  {
    $this->defaultFont = $fontName;
  }

  /**
   * Tải cấu hình fields có sẵn
   * @param string $jsonData Dữ liệu dạng json
   */
  public function LoadFields($jsonData)
  {
    $this->fields = json_decode($jsonData);
  }

  /**
   * Tải cấu hình fields có sẵn trong file
   * @param string $filePath Đường dẫn đến file config
   */
  public function LoadFieldsInFile($filePath)
  {
    $jsonData = file_get_contents($filePath);
    $this->fields = json_decode($jsonData);
  }

  /**
   * Lấy cấu hình fields ở dạng json
   */
  public function GetFieldsAsJson()
  {
    return json_encode($this->fields, JSON_PRETTY_PRINT);
  }

  /**
   * Tải datas có sẵn
   * @param string $jsonData Dữ liệu dạng json
   */
  public function LoadDatas($jsonData)
  {
    $this->datas = json_decode($jsonData);
  }

  /**
   * Lấy datas ở dạng json
   */
  public function GetDatasAsJson()
  {
    return json_encode($this->datas, JSON_PRETTY_PRINT);
  }

  /**
   * Tải datas có sẵn trong file
   * @param string $filePath Đường dẫn đến file config
   */
  public function LoadDatasInFile($filePath)
  {
    $jsonData = file_get_contents($filePath);
    $this->datas = json_decode($jsonData, true);
  }


  /**
   * Tạo empty field để chèn vào hình ảnh
   * @param string $page Thứ tự trang trên template (trang đầu tiên là 1)
   * @param string $name Tên field (không được trùng với những field khác)
   * @param int $x Tọa độ phương ngang trên trang (tính từ bìa trái sang, đơn vị là mm)
   * @param int $y Tọa độ phương dọc trên trang (tính từ bịa trên xuống, đơn vị là mm)
   * @param int $width Độ rộng của vùng chứa ảnh
   * @param int $height Độ cao của vùng chứa ảnh
   */
  public function CreateImageField($page, $name, $x, $y, $width, $height)
  {
    $field = (object)[
      'page' => $page,
      'name' => $name,
      'type' => 'img',
      'x' => $x,
      'y' => $y,
      'width' => $width,
      'height' => $height
    ];

    array_push($this->fields, $field);

    return $field;
  }

  /**
   * Ghi đè một file blank để che data
   * @param string $page Thứ tự trang trên template (trang đầu tiên là 1)
   * @param int $x Tọa độ phương ngang trên trang (tính từ bìa trái sang, đơn vị là mm)
   * @param int $y Tọa độ phương dọc trên trang (tính từ bịa trên xuống, đơn vị là mm)
   * @param int $width Độ rộng của vùng xóa
   * @param int $height Độ cao của vùng xóa
   */
  public function CreateEraseField($page, $x, $y, $width, $height)
  {
    array_push($this->fields, (object)[
      'page' => $page,
      'type' => 'erase',
      'x' => $x,
      'y' => $y,
      'width' => $width,
      'height' => $height
    ]);
  }

  /**
   * Tạo empty field để chèn vào văn bản
   * @param string $page Thứ tự trang trên template (trang đầu tiên là 1)
   * @param string $name Tên field (không được trùng với những field khác)
   * @param int $x Tọa độ phương ngang trên trang (tính từ bìa trái sang, đơn vị là mm)
   * @param int $y Tọa độ phương dọc trên trang (tính từ bịa trên xuống, đơn vị là mm)
   * @param int $fontFamily Tên đầy đủ của font (mặc định sẽ lấy font đã setup ở hàm SetDefaultFont)
   * @param int $fontStyle Kiểu chữ (mặc định là Regular) ; 'R'=Regular, 'B'=Bold, 'I'=Italic, 'BI'=BoldItalic)
   * @param int $fontSize Kích cỡ chữ (mặc định là 12pt)
   */
  public function CreateTextField($page, $name, $x, $y, $fontFamily = '', $fontStyle = '', $fontSize = 0)
  {
    if ($fontFamily == '')
      $fontFamily = $this->defaultFont;
    if ($fontSize == 0)
      $fontSize = $this->defaultFontSize;

    $field = (object)[
      'page' => $page,
      'name' => $name,
      'type' => 'text',
      'x' => $x,
      'y' => $y,
      'fontFamily' => $fontFamily,
      'fontStyle' => $fontStyle,
      'fontSize' => $fontSize
    ];

    array_push($this->fields, $field);

    return $field;
  }

  /**
   * Tạo 1 khung chữ nhật
   * @param string $page Thứ tự trang trên template (trang đầu tiên là 1)
   * @param int $x Tọa độ phương ngang trên trang (tính từ bìa trái sang, đơn vị là mm)
   * @param int $y Tọa độ phương dọc trên trang (tính từ bịa trên xuống, đơn vị là mm)
   * @param int $width Độ rộng của khung
   * @param int $height Độ cao của khung
   */
  public function CreateBorderField($page, $x, $y, $width, $height)
  {
    $field = (object)[
      'page' => $page,
      'type' => 'border',
      'x' => $x,
      'y' => $y,
      'width' => $width,
      'height' => $height
    ];

    array_push($this->fields, $field);

    return $field;
  }

  /**
   * Tạo 1 khung chữ ký
   * @param string $page Thứ tự trang trên template (trang đầu tiên là 1)
   * @param int $x Tọa độ phương ngang trên trang (tính từ bìa trái sang, đơn vị là mm)
   * @param int $y Tọa độ phương dọc trên trang (tính từ bịa trên xuống, đơn vị là mm)
   * @param int $width Độ rộng của khung
   * @param int $height Độ cao của khung
   */
  public function CreateSignatureField($page, $name, $x, $y, $width, $height)
  {
    array_push($this->fields, (object)[
      'page' => $page,
      'type' => 'signature',
      'name' => $name,
      'x' => $x,
      'y' => $y,
      'width' => $width,
      'height' => $height
    ]);
  }

  public function FillSignature($name, $title, $datetime, $tickimg, $signername)
  {
    $this->datas[$name] = (object)[
      'page' => 1,
      'title' => $title,
      'datetime' => $datetime,
      'tickimg' => $tickimg,
      'signername' => $signername
    ];
  }

  /**
   * Chèn dữ liệu vào các field
   * @param string $name UniqueName của field từ hàm CreateTextField và CreateImageField
   * @param string $data Nếu field là Text, data sẽ là văn bản
   * @param string $data Nếu field là Image, data sẽ là đường dẫn đến hình ảnh
   */
  public function FillData($name, $data)
  {
    $this->datas[$name] = $data;
  }


  private function ConvertPointToMm($point)
  {
    return $point * 0.352778;
  }

  /**
   * Tiến hành render từng trang một theo thông số của file template và fields, datas đã import
   * @param bool $isRenderData Nếu là true, những dữ liệu đã được FillData(...) sẽ được hiển thị trên field tương ứng. Đối với những field không có dữ liệu sẽ chỉ hiển thị khung layout
   * @param bool $isRenderData Nếu là false, chỉ hiển thị layout của toàn bộ fields
   */
  public function RenderPDFData($isRenderData)
  {
    //Import toàn bộ các trang
    for ($page = 1; $page <= $this->pageCount; $page++) {
      //Import dữ liệu từng trang
      $pageID = $this->importPage($page);
      $pageInfo = $this->getTemplateSize($pageID);

      $this->AddPage($pageInfo['orientation'], array($pageInfo['width'], $pageInfo['height']));

      $this->useTemplate($pageID);

      $this->RenderFieldData($page, $isRenderData);

      $this->pageInfo[$page] = (object)[
        'number' => $page,
        'orientation' => $pageInfo['orientation'],
        'width' => round($pageInfo['width'], 1),
        'height' => round($pageInfo['height'], 1)
      ];
    }
  }

  /**
   * Tiến hành đổ Text vào field
   * @param object $field Field cần đổ text
   * @param bool $isRenderData Nếu là true, những dữ liệu đã được FillData(...) sẽ được hiển thị trên field tương ứng. Đối với những field không có dữ liệu sẽ chỉ hiển thị khung layout
   * @param bool $isRenderData Nếu là false, chỉ hiển thị layout của toàn bộ fields
   */
  private function RenderText($field, $isRenderData)
  {
    $this->SetFont($field->fontFamily, $field->fontStyle, $field->fontSize);
    $this->SetXY($field->x, $field->y);

    $dataText = $this->datas[$field->name] ?? '';
    if ($isRenderData && $dataText) {
      $this->Write(0, $dataText);
    } else {
      $this->Write(0, $field->name);

      $this->Rect(
        $field->x + $this->ConvertPointToMm($field->fontSize) / 5,
        $field->y - $this->ConvertPointToMm($field->fontSize) / 2,
        $this->GetStringWidth($field->name),
        $this->ConvertPointToMm($field->fontSize),
        'D'
      );
    }
  }

  /**
   * Tiến hành đổ Image vào field
   * @param object $field Field cần đổ Image
   * @param bool $isRenderData Nếu là true, những dữ liệu đã được FillData(...) sẽ được hiển thị trên field tương ứng. Đối với những field không có dữ liệu sẽ chỉ hiển thị khung layout
   * @param bool $isRenderData Nếu là false, chỉ hiển thị layout của toàn bộ fields
   */
  private function RenderImage($field, $isRenderData)
  {
    $dataImage = $this->datas[$field->name] ?? '';
    if ($isRenderData && $dataImage) {
      $this->Image($dataImage, $field->x, $field->y, $field->width);
    } else {
      $this->Rect($field->x, $field->y, $field->width, $field->height, 'D');
    }
  }

   /**
   * Ghi đè
   * @param object $field Field cần đổ Border
   */
  private function RenderErase($field) {
    $this->SetFillColor(255, 255, 255);
    $this->Rect($field->x, $field->y, $field->width, $field->height, 'F');
  }

   /**
   * Vẽ Border
   * @param object $field Field cần đổ Border
   */
  private function RenderBorder($field) {
    $this->SetDrawColor(255, 0, 0);
    $this->Rect($field->x, $field->y, $field->width, $field->height, 'D');
  }

  /**
   * Tiến hành đổ dữ liệu từ FillData() vào cái các fields trong trang
   * @param int $page Số thứ tự trang sẽ render
   * @param bool $isRenderData Nếu là true, những dữ liệu đã được FillData(...) sẽ được hiển thị trên field tương ứng. Đối với những field không có dữ liệu sẽ chỉ hiển thị khung layout
   * @param bool $isRenderData Nếu là false, chỉ hiển thị layout của toàn bộ fields
   */
  private function RenderFieldData($page, $isRenderData)
  {
    foreach ($this->fields as $field) {
      if ($field->page == $page) {
        if ($field->type == 'text') {
          $this->RenderText($field, $isRenderData);
        } else if ($field->type == 'img') {
          $this->RenderImage($field, $isRenderData);
        } else if ($field->type == 'erase') {
          $this->RenderErase($field);
        } else if ($field->type == 'border') {
          $this->RenderBorder($field);
        } else if ($field->type == 'signature') {
          $dataSignature = $this->datas[$field->name];

          $tickwidth = 15;
          $this->SetFont($this->defaultFont, '', $this->defaultFontSize);
          $tickfieldname =  $dataSignature->signername. '&' . $field->x . '&' . $field->y.'tick';
          $tickx = $field->x + ($field->width - $tickwidth) / 2;
          $ticky = $field->y + 1;
          $tickfield = $this->CreateImageField($page, $tickfieldname, $tickx, $ticky, $tickwidth, 0);
          $this->FillData($tickfieldname,  $dataSignature->tickimg);
          $this->RenderImage($tickfield, true);

          $titlefieldname = $dataSignature->title . '&' . $field->x . '&' . $field->y;
          $titlex = $field->x + ($field->width - $this->GetStringWidth($dataSignature->title) ) / 2;
          $titley =  $field->y + $this->ConvertPointToMm($this->defaultFontSize);
          $titlefield = $this->CreateTextField($page, $titlefieldname, $titlex, $titley);
          $this->FillData($titlefieldname, $dataSignature->title);
          $this->RenderText($titlefield, true);

          $timefieldname =  $dataSignature->datetime . '&' . $field->x . '&' . $field->y;
          $timex = $field->x + ($field->width - $this->GetStringWidth($dataSignature->datetime)) / 2;
          $timey = $titley + $this->ConvertPointToMm($this->defaultFontSize) ;
          $timefield = $this->CreateTextField($page, $timefieldname, $timex, $timey);
          $this->FillData($timefieldname,  $dataSignature->datetime);
          $this->RenderText($timefield, true);

          $borderx = $field->x + ($field->width - $this->GetStringWidth($dataSignature->datetime)) / 2;
          $bordery =  $field->y;
          $borderwidth = max($this->GetStringWidth($dataSignature->title), $this->GetStringWidth($dataSignature->datetime)) * 1.05;
          $borderheight = ($timey + $this->ConvertPointToMm($this->defaultFontSize) - $field->y);
          $borderfield = $this->CreateBorderField($page, $borderx, $bordery, $borderwidth , $borderheight);
          $this->RenderBorder($borderfield);

          if ($dataSignature->signername != '') {
            $signerfieldname =  $dataSignature->signername . '&' . $field->x . '&' . $field->y;
            $signerx = $field->x + ($field->width - $this->GetStringWidth($dataSignature->signername) * 1.1) / 2;
            $signery = $field->y + $field->height - $this->ConvertPointToMm($this->defaultFontSize);
            $signerfield = $this->CreateTextField($page, $signerfieldname, $signerx, $signery, '', 'B');
            $this->FillData($signerfieldname,  $dataSignature->signername);
            $this->RenderText($signerfield, true);
          }
        }
      }
    }
  }

  /**
   * - Trực tiếp tải file PDF
   * - Lưu ý: Phải chạy hàm RenderPDFData() trước khi export
   * @param string $fileName Tên file *.pdf
   */
  public function ExportFileDirectly($fileName)
  {
    $this->Output($fileName, "D", true);
  }

  /**
   * - Lưu file PDF vào đường dẫn trong disk
   * - Lưu ý: Phải chạy hàm RenderPDFData() trước khi export
   * @param string $filePath Đường dẫn & tên file *.pdf
   */
  public function ExportFileToDisk($filePath)
  {
    $this->Output($filePath, "F", true);
  }

  /**
   * - Gửi file PDF trực tiếp vào website
   * - Lưu ý: Phải chạy hàm RenderPDFData() trước khi export
   */
  public function ExportFileToBrowser()
  {
    return $this->Output('', "I", true);
  }

  /**
   * - Trả về dữ liệu thô của file PDF
   * - Lưu ý: Phải chạy hàm RenderPDFData() trước khi export
   * @return string Dữ liệu thô file *.pdf
   */
  public function ExportFileAsString()
  {
    return $this->Output('', "S", true);
  }
}
