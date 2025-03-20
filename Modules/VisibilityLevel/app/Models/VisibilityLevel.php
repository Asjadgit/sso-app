<?php

namespace Modules\VisibilityLevel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Filter\Models\CustomFilter;
use Modules\TemplateConfiguration\Models\TemplateConfiguration;
use Modules\VisibilityGroup\Models\VisibilityGroup;

// use Modules\VisibilityLevel\Database\Factories\VisibilityLevelFactory;

class VisibilityLevel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name','description'];

    public function templates()
    {
        return $this->belongsToMany(TemplateConfiguration::class, 'template_visibility');
    }

    public function visibilityGroups()
    {
        return $this->belongsToMany(VisibilityGroup::class, 'visibility_group_visibility_level');
    }

    /**
     * Get the custom filters associated with this visibility level.
     */
    public function customFilters()
    {
        return $this->belongsToMany(CustomFilter::class, 'custom_filter_visibility_level');
    }

    // protected static function newFactory(): VisibilityLevelFactory
    // {
    //     // return VisibilityLevelFactory::new();
    // }
}
