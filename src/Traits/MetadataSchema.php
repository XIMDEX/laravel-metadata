<?php

namespace Metadata\Traits;

use Metadata\Enums\Metadata;
use Metadata\Models\MetadataSection;

trait MetadataSchema
{
    protected function getMetadataForm(string ...$sections)
    {
        $metadata = new MetadataSection;
        $first = true;
        $form = [];
        
        foreach ($sections as $section) {
            $method = 'orWhere';
            if ($first) {
                $method = 'where';
                $first = false;
            }
            $metadata->$method('name', $section);
        }
            
        $data = $metadata->with(['groups' => function ($query) {
                $query->select('metadata_groups.metadata_section_id','metadata_groups.id', 'metadata_groups.name')
                    ->with(['metadata' => function ($query) {
                        $query->select('metadata.id', 'metadata.name', 'metadata.default', 'metadata.type');
                    }]);
            }])
            ->get();
        
        foreach ($data ?? [] as $value) {
            $form[] = [
                'name' => $value->name,
                'title' => strtoupper($value->name),
                'api' => false,
                'tabs' => $this->setTabs($value->groups)
            ];
        }

        return $form;
    }

    protected function setTabs($tabs) : array
    {
        $result = [];
        foreach ($tabs as $tab) {
            $result[] = [
                'title' => ucfirst($tab->name),
                'fields' =>  $this->setFields($tab->metadata, $tab->id)
            ];
        }

        return $result;
    }

    protected function setFields($fields, string $group)
    {
        $result = [];
        foreach ($fields as $field) {
            $_field = [
                'object' => [
                    'realName' => "metadata[{$group}][{$field->id}]",
                    'key' => "metadata[{$group}][{$field->id}]",
                    'label' => $field->name
                ],
            ];

            $result[] = array_merge_recursive($_field, $this->setFieldType($field));
        }

        return $result;
    }

    protected function setFieldType($field)
    {
        $type = $field->type ?? 'text';
        $result = [
            'type' => 'text'
        ];

        if ($type === Metadata::TYPE_ARRAY) {
            $options = $field->default ?? [];
            foreach ($options as $key => $option) {
                $options[$key] = [
                    'key' => $option,
                    'value' => $option,
                ];
            }

            $result =  [
                'type' => 'dropdown',
                'object' => [
                    'options' => $options ?? []
                ]
            ];
        }

        return $result;
    }
}