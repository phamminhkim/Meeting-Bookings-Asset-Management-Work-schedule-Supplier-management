<?php

namespace App\Models\work\workflow;

use Illuminate\Database\Eloquent\Model;

class WlworkflowJobType extends Model
{
    protected $table = 'wlworkflow_jobtypes';
    protected $fillable = [
        'id', 'code', 'name', 'icon', 'keyword',
        'is_require_depends', 'require_depends_text',
        'is_can_be_created', 'is_response_type'
    ];

    protected $casts = [
        'is_require_depends' => 'boolean',
        'is_can_be_created' => 'boolean',
        'is_response_type' => 'boolean',
    ];
}
