<?php
namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

class Domain extends BaseDomain
{
    protected $fillable = ['domain', 'tenant_id', 'is_primary'];

    public function makePrimary(): static
    {
        $this->update(['is_primary' => true]);
        $this->tenant->setRelation('primary_domain', $this);
        return $this;
    }

    protected static function booted()
    {
        static::saved(function (self $model) {
            if ($model->is_primary) {
                $model->tenant->domains()
                    ->where('id', '!=', $model->id)
                    ->update(['is_primary' => false]);
            }
        });
    }
}
