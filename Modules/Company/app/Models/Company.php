<?php

namespace Modules\Company\Models;

use App\Models\CentralUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Contact\Models\Contact;
use Modules\Label\Models\Label;

// use Modules\Company\Database\Factories\CompanyFactory;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'website',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'industry',
        'no_of_employees',
        'years_established',
        'annual_revenue',
        'tax_id',
        'description',
        'parent_company_id',
        'central_owner_user_id'
    ];

    /**
     * Parent Company Relationship (Self-referencing)
     * A company can have a parent company.
     */
    public function parentCompany()
    {
        return $this->belongsTo(Company::class, 'parent_company_id');
    }

    /**
     * Child Company Relationship (Self-referencing)
     * A company can have multiple child companies/subsidairies.
     */
    public function childCompanies()
    {
        return $this->hasMany(self::class, 'parent_company_id');
    }

    public function owner()
    {
        return $this->belongsTo(CentralUser::class, 'central_owner_user_id');
    }

    // withPivot() ensures extra columns (position, joined_at, left_at, shareholder_status) are accessible.
    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_companies')
            ->withPivot('position', 'joined_at', 'left_at', 'shareholder_status')
            ->withTimestamps();
    }

    // Many-to-Many Relationship with Labels
    public function labels()
    {
        return $this->belongsToMany(Label::class, 'company_labels')->withTimestamps();
    }

    public function fields()
    {
        return $this->hasMany(CompanyField::class, 'company_id');
    }

    // protected static function newFactory(): CompanyFactory
    // {
    //     // return CompanyFactory::new();
    // }
}
