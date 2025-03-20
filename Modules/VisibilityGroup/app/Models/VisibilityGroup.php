<?php

namespace Modules\VisibilityGroup\Models;

use App\Models\CentralUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Filter\Models\CustomFilter;

// use Modules\VisibilityGroup\Database\Factories\VisibilityGroupFactory;

class VisibilityGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'description', 'parent_id'];

    protected $casts = [
        'parent_id' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(VisibilityGroup::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(VisibilityGroup::class, 'parent_id');
    }

    public function users()
    {
        return $this->belongsToMany(CentralUser::class, 'user_visibility_group');
    }

    /**
     * Get the custom filters associated with this visibility group.
     */
    public function customFilters()
    {
        return $this->belongsToMany(CustomFilter::class, 'custom_filter_visibility_group');
    }

    // protected static function newFactory(): VisibilityGroupFactory
    // {
    //     // return VisibilityGroupFactory::new();
    // }
}
