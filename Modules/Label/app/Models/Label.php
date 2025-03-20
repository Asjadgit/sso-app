<?php

namespace Modules\Label\Models;

use App\Models\CentralUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Models\Company;
use Modules\Contact\Models\Contact;

// use Modules\Label\Database\Factories\LabelFactory;

class Label extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'name',
        'color',
        'type',
        'subtype',
        'description',
    ];


    // Relationship: Label belongs to a User
    public function user()
    {
        return $this->belongsTo(CentralUser::class);
    }

    // Many-to-Many Relationship with Contacts
    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_labels')->withTimestamps();
    }

    // Many-to-Many Relationship with Companies
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_labels')->withTimestamps();
    }

    // protected static function newFactory(): LabelFactory
    // {
    //     // return LabelFactory::new();
    // }
}
