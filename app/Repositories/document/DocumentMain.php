<?php

namespace App\Repositories\document;

use App\Models\document\Document;
use App\Models\shared\DocumentType;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class DocumentMain extends DocumentBase
{
    public static function create(Request $request)
    {

        $obj = null;
        $documentType = null;

        $documentType = DocumentType::find($request->document_type_id);

        $doctype = "";
        if ($documentType) {
            $doctype = $documentType->code;
        }

        switch ($doctype) {

            case Ultils::$MODULE_DOCUMENT_PDNS: // Trình duyệt vượt ngân sách
                $obj = new DocumentPDNS($request);
                break;
            case Ultils::$MODULE_DOCUMENT_BGIA: // Trình duyệt tờ trình chung
                $obj = new DocumentBGIA($request);
                break;
            case Ultils::$MODULE_DOCUMENT_CHHH: // Trình duyệt tờ chi hoa hồng
                $obj = new DocumentCHHH($request);
                break;
            case Ultils::$MODULE_DOCUMENT_TKCH: // Trình ký chung
                $obj = new DocumentTKCH($request);
                break;
            case Ultils::$MODULE_DOCUMENT_TKRT: // Trình ký chung (riêng tư)
                $obj = new DocumentTKRT($request);
                break;
            default:

                $obj = new DocumentBase($request);

                break;
        }

        return  $obj;
    }
}
