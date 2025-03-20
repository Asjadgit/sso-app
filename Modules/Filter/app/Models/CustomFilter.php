<?php

namespace Modules\Filter\Models;

use App\Models\CentralUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\VisibilityGroup\Models\VisibilityGroup;
use Modules\VisibilityLevel\Models\VisibilityLevel;

// use Modules\Filter\Database\Factories\CustomFilterFactory;

class CustomFilter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'filter_criteria',
    ];

    protected $casts = [
        'filter_criteria' => 'array',
    ];

    /**
     * Get the user who created the filter.
     */
    public function user()
    {
        return $this->belongsTo(CentralUser::class);
    }

     /**
     * Get the visibility levels associated with this custom filter.
     */
    public function visibilityLevels()
    {
        return $this->belongsToMany(VisibilityLevel::class, 'custom_filter_visibility_level');
    }

    /**
     * Get the visibility groups associated with this custom filter.
     */
    public function visibilityGroups()
    {
        return $this->belongsToMany(VisibilityGroup::class, 'custom_filter_visibility_group');
    }

    // protected static function newFactory(): CustomFilterFactory
    // {
    //     // return CustomFilterFactory::new();
    // }
}
