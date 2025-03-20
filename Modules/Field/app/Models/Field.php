<?php

namespace Modules\Field\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Contact\Models\ContactField;

// use Modules\Field\Database\Factories\FieldFactory;

class Field extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'wp_fields';
    protected $fillable = [
        'form_id',
        'source_id',
        'source',
        'wp_field_id',
        'label',
        'name',
        'type',
        'field_type',
        'required',
        'default_value',
        'placeholder',
        'validation_rules',
        'position',
        'help_text',
        'allowed_file_types',
        'max_file_size',
        'conditional_logic',
        'min_value',
        'max_value',
        'max_length',
        'icon_url',
        'calculation_is_enabled',
        'calculation_code',
        'calculation_code_php',
        'calculation_code_js',
    ];

    protected $casts = [
        'conditional_logic' => 'array',
        'required' => 'boolean',
        'calculation_is_enabled' => 'boolean',
    ];

    /**
     * Relationship with the WpForm model.
     */
    public function form()
    {
        return $this->belongsTo(WpForm::class, 'form_id');
    }

    /**
     * Relationship with the WordPress field (if applicable).
     */
    public function wpField()
    {
        return $this->belongsTo(Field::class, 'wp_field_id');
    }

    public function choices()
    {
        return $this->hasMany(FieldChoice::class, 'field_id');
    }

    public function contactFields()
    {
        return $this->hasMany(ContactField::class, 'field_id');
    }

    // protected static function newFactory(): FieldFactory
    // {
    //     // return FieldFactory::new();
    // }
}
