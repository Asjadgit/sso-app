<?php

namespace Modules\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Label\Models\Label;

// use Modules\Contact\Database\Factories\ContactLabelFactory;

class ContactLabel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['contact_id', 'label_id'];

    // Relationship with Contact
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    // Relationship with Label
    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    // protected static function newFactory(): ContactLabelFactory
    // {
    //     // return ContactLabelFactory::new();
    // }
}
