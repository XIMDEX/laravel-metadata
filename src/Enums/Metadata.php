<?php

namespace Metadata\Enums;

use VD\Core\Enums;

class Metadata extends Enums
{
    const TYPE_INT = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_TEXT = 'text';
    const TYPE_BOOL = 'boolean';
    const TYPE_DATE = 'date';
    const TYPE_ARRAY = 'array';

    protected static function data()
    {
        $data = [];
        foreach (Metadata::getConstants() as $constant) {
            $data[$constant] = [
                'title' => $constant
            ];
        }
        return $data;
    }

    public static function values(array $less = [])
    {
        return array_keys(Metadata::getValues('title', $less));
    }

    public static function default()
    {
        return static::TYPE_TEXT;
    }
}