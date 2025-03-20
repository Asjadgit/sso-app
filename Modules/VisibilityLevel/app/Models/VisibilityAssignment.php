<?php

namespace Modules\VisibilityLevel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\VisibilityLevel\Database\Factories\VisibilityAssignmentFactory;

class VisibilityAssignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $table = 'visibility_assignments';

    protected $fillable = ['items_id','items_type','visibility_level_id'];

    public function items()
    {
        return $this->morphTo();
    }

    public function visibilitylevel()
    {
        return $this->belongsTo(VisibilityLevel::class,'visibility_level_id');
    }

    // protected static function newFactory(): VisibilityAssignmentFactory
    // {
    //     // return VisibilityAssignmentFactory::new();
    // }
}
