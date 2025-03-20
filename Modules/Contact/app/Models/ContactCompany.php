<?php

namespace Modules\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Models\Company;

// use Modules\Contact\Database\Factories\ContactCompanyFactory;

class ContactCompany extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'contact_id',
        'company_id',
        'position',
        'joined_at',
        'left_at',
        'shareholder_status',
    ];

    /**
     * Relationship with Contact
     * A record belongs to a specific contact.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Relationship with Company
     * A record belongs to a specific company.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // protected static function newFactory(): ContactCompanyFactory
    // {
    //     // return ContactCompanyFactory::new();
    // }
}
