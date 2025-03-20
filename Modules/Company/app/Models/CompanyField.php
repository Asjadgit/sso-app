<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Field\Models\Field;

// use Modules\Company\Database\Factories\CompanyFieldFactory;

class CompanyField extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'field_id',
        'field_label',
        'field_value',
        'group_id',
    ];

    /**
     * Relationship with Company model.
     * Each company field belongs to a specific company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Relationship with WpField model.
     * Each company field corresponds to a specific wp_field.
     */
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    /**
     * Relationship with Group model (if applicable).
     * Each company field can optionally belong to a group.
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    // protected static function newFactory(): CompanyFieldFactory
    // {
    //     // return CompanyFieldFactory::new();
    // }
}
