<?php

namespace Metadata\Models;

use Ximdex\Core\Database\Eloquent\Model;

class MetadataValue extends Model
{
    protected $fillable = [
        'value',
        'owner_id',
        'metadata_metadata_group_id',
    ];
}