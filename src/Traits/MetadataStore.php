<?php

namespace Metadata\Traits;

use Metadata\Models\MetadataValue;
use Metadata\Models\MetadataSection;
use Illuminate\Database\Eloquent\Model;
use Metadata\Models\MetadataMetadataGroup;

trait MetadataStore
{

    protected function getMetadataValue(Model $model, string $sections = null)
    {
        $owner_id = $this->generateId($model);
        
        $data = MetadataSection::where('name', '=', $sections)
            ->with(['groups' => function ($query) use ($owner_id) {
                $query->select(['metadata_groups.metadata_section_id', 'metadata_groups.id'])
                    ->with(['metadata' => function ($query) use ($owner_id) {
                        $query->select('metadata.id', 'metadata.name')
                            ->with(['values' => function ($query) use ($owner_id){
                                $query->select('value')
                                    ->where('owner_id', '=', $owner_id);
                            }]);
                    }]);
            }])->get();

        $values = [];
        foreach ($data as $section) {
            $values = $this->prepareValues($section);
        }   
        
        $result = [
            'metadata' => $values
        ];

        return $result;
    }

    protected function prepareValues($data)
    {
        $groups = $data['groups'];
        $result = [];
        
        foreach ($groups as $group) {
            foreach ($group->metadata as $metadata) {
                if ($metadata->values->isEmpty()) {
                    continue;
                }
                $result[$group->id][$metadata->id] = $metadata->values->first()->value;
            }
        }

        return $result;
    }

    protected function saveMetadataValue(string $group, string $metadata, $value, Model $model) : bool
    {
        try {
            $modelId = $this->generateId($model);
            $metadataMetadataGroup = MetadataMetadataGroup::where('metadata_group_id', '=', $group)
                ->where('metadata_id', '=', $metadata)
                ->firstOrFail();

            $metadataValue = MetadataValue::where('owner_id', '=', $modelId)
                ->where('metadata_metadata_group_id', '=', $metadataMetadataGroup->id)
                ->first();

            if (!is_null($metadataValue)) {
                $metadataValue->value = $value;
                $metadataValue->save();
            } else {
                MetadataValue::create([
                    'value' => $value,
                    'owner_id' => $modelId,
                    'metadata_metadata_group_id' => $metadataMetadataGroup->id
                ]);
            }

            $result = true;
        } catch (\Exception $ex) {
            $result = false;
        }

        return $result;
    }

    protected function generateId(Model $model) : string
    {
        return "{$this->getModelName($model)}_{$model->id}";
    }

    private function getModelName(Model $model) : string
    {
        $class = class_basename($model);
        return $class;
    }
}
