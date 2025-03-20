<?php

namespace Modules\Contact\Models;

use App\Models\CentralUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Models\Company;
use Modules\Label\Models\Label;

// use Modules\Contact\Database\Factories\ContactFactory;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'owner_user_id',
        'referred_by',
        'source_contact_id',
    ];

    // Contact belongs to a User (Owner)
    public function owner()
    {
        return $this->belongsTo(CentralUser::class, 'owner_user_id');
    }

    // Self-referencing: Contact referred by another Contact
    public function referredBy()
    {
        return $this->belongsTo(Contact::class, 'referred_by');
    }

    // Contacts that this Contact has referred
    public function referrals()
    {
        return $this->hasMany(Contact::class, 'referred_by');
    }

    // Self-referencing: Contact that is the source of this Contact
    public function sourceContact()
    {
        return $this->belongsTo(Contact::class, 'source_contact_id');
    }

    // Contacts that have this Contact as their source
    public function sourcedContacts()
    {
        return $this->hasMany(Contact::class, 'source_contact_id');
    }

    public function users()
    {
        return $this->belongsToMany(CentralUser::class, 'contact_user_follows')->withTimestamps();
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'contact_companies')
            ->withPivot('position', 'joined_at', 'left_at', 'shareholder_status')
            ->withTimestamps();
    }

    // Many-to-Many Relationship with Labels
    public function labels()
    {
        return $this->belongsToMany(Label::class, 'contact_labels')->withTimestamps();
    }

    public function contactFields()
    {
        return $this->hasMany(ContactField::class);
    }

    // protected static function newFactory(): ContactFactory
    // {
    //     // return ContactFactory::new();
    // }
}
