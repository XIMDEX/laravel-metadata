<?php

namespace Metadata\Models;

use Ximdex\Core\Database\Eloquent\Model;

class MetadataGroup extends Model
{
    protected $fillable = [
        'metadata_section',
        'name',
    ];

    protected $casts = [
        'name' => 'string',
    ];

    protected $_relations = [
        'section' => [
            'type' => 'belongsTo',
            'model' => MetadataSection::class
        ],
        'metadata' => [
            'type' => 'belongsToMany',
            'model' => Metadata::class,
            'foreignPivotKey' => 'metadata_group_id',
            'relatedPivotKey' => 'metadata_id',
        ]
    ];
}