<?php

use App\Models\work\workflow\runtime\WlworkflowDoc;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOriginIdToWlworkflowDocs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $workflow_docs = WlworkflowDoc::all();
        foreach ($workflow_docs as $document) {
            $document->load('wlworkflow');
            $document->original_id = $document->wlworkflow->original_id;
            $document->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     
    }
}
