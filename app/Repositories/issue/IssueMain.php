<?php
namespace App\Repositories\issue;

use App\Models\issue\Issue;
use App\Models\shared\DocumentType;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class IssueMain extends IssueBase{
    public static function create(Request $request)
    {

        $obj = null;
        $documentType = null;

        $documentType = DocumentType::find( $request->document_type_id);

        $doctype = "";
        if($documentType){
            $doctype = $documentType->code;
        }

        switch ($doctype) {

            case Ultils::$MODULE_ISSUE_YCDV: // Trình duyệt vượt ngân sách
                $obj= new IssueYCDV($request);
                break;
            default:
                $obj= new IssueBase($request);
                break;
        }

        return  $obj;
    }
}
?>
