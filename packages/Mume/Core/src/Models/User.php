<?php

namespace Mume\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Mume\Core\Common\CommonConst;

/**
 * Class User
 *
 * @package Mume\Core\Models
 */
class User extends BaseAuth
{
    use HasApiTokens, HasFactory, Notifiable;

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'latest_update_at';

    public const ADMIN_ID = 1;

    public const USER_IMAGE_UPLOAD_DIR = 'users';

    protected $table = 'users';

    protected $hidden = ['password', 'remember_token'];

    protected $fillable = [
        'username',
        'email',
        'password',
        'name',
        'phone_number',
        'gender',
        'birth_date',
        'avatar',
        'description',
        'role_id',
        'is_active',
        'remember_token',
        'created_by_id',
        'created_at',
        'latest_update_by_id',
        'latest_update_at',
        'deleted_by_id',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $attributes = [
        'is_active' => CommonConst::IS_ACTIVE,
    ];

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return BelongsTo
     */
    public function parent():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    /**
     * @return hasMany
     */
    public function children():hasMany
    {
        return $this->hasMany(User::class, 'id', 'created_by_id');
    }
}
