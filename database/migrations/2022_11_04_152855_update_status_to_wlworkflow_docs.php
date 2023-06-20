<?php

use App\Models\work\workflow\runtime\WlworkflowDoc;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusToWlworkflowDocs extends Migration
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
            if ($document->status == null) {
                $document->status = 0;
                $document->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wlworkflow_docs', function (Blueprint $table) {
            //
        });
    }
}
