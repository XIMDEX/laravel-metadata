<?php

namespace Metadata\Models;

use VD\Models\Model;
use Metadata\Enums\Metadata as EnumMetadata;

class Metadata extends Model
{
    protected $_relations = [
        'section' => [
            'type' => 'belongsTo',
            'model' => MetadataSection::class
        ],
        'values' => [
            'type' => 'belongsToMany',
            'relation' => 'metadata_metadata_group',
            'model' => MetadataValue::class,
            'foreignPivotKey' => 'metadata_id',
            'relatedPivotKey' => 'metadata_value_id',
        ]
    ];

    protected $fillable = [
        'name',
        'default',
        'type'
    ];

    protected $hidden = [
        'pivot'
    ];

    protected $casts = [
        'name' => 'string',
        'type' => 'string',
        'default' => 'value'
    ];

    public function setTypeAttributte(string $value)
    {
        if (!in_array($value, EnumMetadata::values())) {
            $value = EnumMetadata::default();
        }

        $this->attributes['type'] = $value;
        return $this;
    }

    protected function getCastType($key) {
        $type = $this->casts[$key] ?? null;
        if ($type === 'value' && !empty($this->type)) {
          return $this->type;
        } else {
          return parent::getCastType($key);
        }
      }
}