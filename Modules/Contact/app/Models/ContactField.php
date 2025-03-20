<?php

namespace Modules\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Field\Models\Field;

// use Modules\Contact\Database\Factories\ContactFieldFactory;

class ContactField extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'contact_id',
        'field_id',
        'field_label',
        'field_value',
        'group_id'
    ];

    /**
     * Relationship with Contact
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Relationship with Field (wp_fields)
     */
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    /**
     * Relationship with Group (if applicable)
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // protected static function newFactory(): ContactFieldFactory
    // {
    //     // return ContactFieldFactory::new();
    // }
}
