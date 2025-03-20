<?php

namespace App;

use App\Models\CentralUser;
use App\Models\Domain;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\TenantPivot;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'main_db_id',
            'name',
            'email',
            'password',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(CentralUser::class, 'tenant_users', 'tenant_id', 'global_user_id', 'id', 'global_id')
            ->using(TenantPivot::class);
    }

    public function primary_domain(): HasOne
    {
        return $this->hasOne(Domain::class)->where('is_primary', true);
    }

    public function route(string $route, array $parameters = [], bool $absolute = true): string
    {
        if (! $this->primary_domain) {
            throw new Exception('Tenant does not have a primary domain.');
        }

        $domain = $this->primary_domain->domain;
        $parts = explode('.', $domain);

        if (count($parts) === 1) {
            $domain = $domain . '.' . config('app.domain');
        }

        return tenant_route($domain, $route, $parameters, $absolute);
    }

    public function impersonationUrl(string $userId): string
    {
        // Use a tenant route of your choice here
        // We'll use 'home' in this example
        $token = tenancy()->impersonate($this, $userId, $this->route('home'), 'web')->token;

        return $this->route('impersonate', ['token' => $token]);
    }
}
