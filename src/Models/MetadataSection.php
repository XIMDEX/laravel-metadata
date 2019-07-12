<?php

namespace Metadata\Models;

use Ximdex\Core\Database\Eloquent\Model;

class MetadataSection extends Model
{
    protected $_relations = [
        'groups' => [
            'type' => 'hasMany',
            'model' => MetadataGroup::class
        ]
    ];

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'string',
    ];
}