<?php

namespace Modules\Field\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Field\Database\Factories\FieldChoiceFactory;

class FieldChoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'wp_field_choices';
    protected $fillable = [
        'field_id',
        'label',
        'value',
        'image',
        'icon',
        'icon_style',
    ];

    /**
     * Relationship with the WpField model.
     */
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    // protected static function newFactory(): FieldChoiceFactory
    // {
    //     // return FieldChoiceFactory::new();
    // }
}
