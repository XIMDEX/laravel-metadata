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
        
        $data = MetadataSection::select(
            'metadata_metadata_group.metadata_group_id as groups',
            'metadata_metadata_group.metadata_id as metadata',
            'metadata_values.value as value'
        )
            ->leftJoin('metadata_groups', 'metadata_groups.metadata_section_id', '=', 'metadata_sections.id')
            ->leftJoin('metadata_metadata_group', 'metadata_groups.id', '=', 'metadata_metadata_group.metadata_group_id')
            ->leftJoin('metadata_values', 'metadata_values.metadata_metadata_group_id', '=', 'metadata_metadata_group.id')
            ->where('metadata_sections.name', '=', $sections)
            ->where('metadata_values.owner_id', '=', $owner_id)
            ->get();

        $values = [];
        foreach ($data as $section) {
            $section = $section->toArray();
            $values[$section['groups']][$section['metadata']] = $section['value'];
        }
        
        $result = [
            'metadata' => $values
        ];

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

    protected function removeMetadata(string $group, string $metadata, Model $model) : bool
    {
        $result = true;
        try {
            $modelId = $this->generateId($model);
            $metadataMetadataGroup = MetadataMetadataGroup::where('metadata_group_id', '=', $group)
                ->where('metadata_id', '=', $metadata)
                ->firstOrFail();

            $metadata = MetadataValue::where('owner_id', '=', $modelId)
                ->where('metadata_metadata_group_id', '=', $metadataMetadataGroup->id)
                ->first();
            
            if (!is_null($metadata)) {
                $result = $metadata->delete();
            }
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
