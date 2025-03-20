<?php

namespace Modules\TemplateConfiguration\Models;

use App\Models\CentralUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\VisibilityLevel\Models\VisibilityLevel;

// use Modules\TemplateConfiguration\Database\Factories\TemplateConfigurationFactory;

class TemplateConfiguration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'entity_type',
        'view_type',
        'central_admin_id',
        'central_user_id',
        'configuration',
        'is_default',
    ];

    protected $casts = [
        'configuration' => 'array'
    ];

    public function admin()
    {
        return $this->belongsTo(CentralUser::class, 'central_admin_id');
    }

    public function user()
    {
        return $this->belongsTo(CentralUser::class, 'central_user_id');
    }

    public function visibilityLevels()
    {
        return $this->belongsToMany(VisibilityLevel::class, 'template_visibility');
    }

    public static function getUserTemplate($userId, $entityType, $viewType)
    {
        return self::where('central_user_id', $userId)
            ->where('entity_type', $entityType)
            ->where('view_type', $viewType)
            ->first()
            ?? self::where('entity_type', $entityType)
            ->where('view_type', $viewType)
            ->where('is_default', 1)
            ->whereHas('visibilityLevels', function ($query) {
                $query->where('name', 'All Users');
            })
            ->first();
    }

    // protected static function newFactory(): TemplateConfigurationFactory
    // {
    //     // return TemplateConfigurationFactory::new();
    // }
}
