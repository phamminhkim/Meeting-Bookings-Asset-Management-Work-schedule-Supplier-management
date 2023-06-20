<?php
namespace App\Traits;

use App\Models\shared\PrintedDoc;

trait HasReportDoc{
    public function printedDocs(){
        return $this->morphMany(PrintedDoc::class,'objectable');
    }
}
