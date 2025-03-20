<?php

namespace App\Models;

use App\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Modules\Company\Models\Company;
use Modules\Contact\Models\Contact;
use Modules\Filter\Models\CustomFilter;
use Modules\Label\Models\Label;
use Modules\Team\Models\Team;
use Modules\TemplateConfiguration\Models\TemplateConfiguration;
use Modules\VisibilityGroup\Models\VisibilityGroup;
use Stancl\Tenancy\Contracts\SyncMaster;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;
use Stancl\Tenancy\Database\Models\TenantPivot;

class CentralUser extends Authenticatable implements SyncMaster
{
    // Note that we force the central connection on this model
    use ResourceSyncing, CentralConnection, HasFactory, Notifiable;
    // use  HasRoles, HasApiTokens;
    public $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_users', 'global_user_id', 'tenant_id', 'global_id')
            ->using(TenantPivot::class);
    }

    public function getTenantModelName(): string
    {
        return User::class;
    }

    public function getGlobalIdentifierKey()
    {
        return $this->getAttribute($this->getGlobalIdentifierKeyName());
    }

    public function getGlobalIdentifierKeyName(): string
    {
        return 'global_id';
    }

    public function getCentralModelName(): string
    {
        return static::class;
    }

    public function getSyncedAttributeNames(): array
    {
        return [
            'name',
            'password',
            'email',
        ];
    }


    public function meta()
    {
        return $this->hasMany(UserMeta::class);
    }

    public function getMeta($key)
    {
        return $this->meta()->where('meta_key', $key)->value('meta_value');
    }

    public function setMeta($key, $value)
    {
        return $this->meta()->updateOrCreate(
            ['meta_key' => $key, 'user_id' => $this->id], // Ensure user_id is included
            ['meta_value' => $value]
        );
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_users');
    }

    public function managedTeam()
    {
        return $this->hasMany(Team::class, 'manager_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'owner_user_id');
    }

    public function userfollows()
    {
        return $this->belongsToMany(Contact::class, 'contact_user_follows')->withTimestamps();
    }

    public function ownedCompanies()
    {
        return $this->hasMany(Company::class, 'owner_user_id');
    }

    // User has many Labels
    public function labels()
    {
        return $this->hasMany(Label::class);
    }

    public function visibilityGroups()
    {
        return $this->belongsToMany(VisibilityGroup::class, 'user_visibility_group');
    }

    // Templates assigned to user
    public function userTemplates()
    {
        return $this->hasMany(TemplateConfiguration::class, 'central_user_id');
    }

    // Templates assigned to admin
    public function adminTemplates()
    {
        return $this->hasMany(TemplateConfiguration::class, 'central_admin_id');
    }

    public function customFilters()
    {
        return $this->hasMany(CustomFilter::class);
    }
}
