<?php

namespace Modules\Team\Models;

use App\Models\CentralUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// use Modules\Team\Database\Factories\TeamFactory;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'manager_id', 'description', 'status'];

    public function manager()
    {
        return $this->belongsTo(CentralUser::class, 'manager_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(CentralUser::class, 'team_users');
    }

    // protected static function newFactory(): TeamFactory
    // {
    //     // return TeamFactory::new();
    // }
}
