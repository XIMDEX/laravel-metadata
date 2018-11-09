<?php

namespace Metadata\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MetadataMetadataGroup extends Pivot
{
    // protected $fillable = [
    //     'required',
    //     'metadata_group',
    //     'metadata',
    //     'metadata_value',
    // ];

    // protected $_relations = [
    //     'metadata' => [
    //         'type' => 'belongsTo',
    //         'model' => Metadata::class
    //     ],
    //     'group' => [
    //         'type' => 'belongsTo',
    //         'model' => MetadataGroup::class
    //     ],
    //     'value' => [
    //         'type' => 'belongsTo',
    //         'model' => MetadataValue::class
    //     ]
    // ];
}