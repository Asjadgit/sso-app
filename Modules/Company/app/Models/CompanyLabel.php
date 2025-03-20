<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Label\Models\Label;

// use Modules\Company\Database\Factories\CompanyLabelFactory;

class CompanyLabel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['company_id', 'label_id'];


    // Relationship with Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Relationship with Label
    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    // protected static function newFactory(): CompanyLabelFactory
    // {
    //     // return CompanyLabelFactory::new();
    // }
}
