<?php

namespace Mume\Core\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Mume\Core\Common\CommonConst;

/**
 * Class Role
 *
 * @package Mume\Core\Models
 */
class Role extends Base
{
    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'latest_update_at';

    public const GUESS_ROLE = 4;

    public const STAFF_ROLE = 3;

    public const MANAGER_ROLE = 2;

    public const  ADMIN_ROLE = 1;

    public const SHORTCUT_NAME_ROLE_ADMIN = 'AD';

    public const SHORTCUT_NAME_ROLE_STAFF = 'SF';

    public const SHORTCUT_NAME_ROLE_MANAGER = 'MG';

    public const SHORTCUT_NAME_ROLE_GUESS = 'GS';

    protected $table = 'roles';

    protected $attributes = [
        'is_active' => CommonConst::IS_ACTIVE,
    ];

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'created_by_id',
        'created_at',
        'latest_update_by_id',
        'latest_update_at',
        'deleted_by_id',
        'deleted_at',
    ];

    /**
     * Trả về danh sách role của hệ thống
     *
     * @return array
     */
    public static function availableRoles(): array
    {
        return [
            self::ADMIN_ROLE,
            self::STAFF_ROLE,
            self::MANAGER_ROLE,
            self::GUESS_ROLE,
        ];
    }

    /**
     * Trả về tên shortcut theo từng role
     *
     * @param int $roleId
     *
     * @return string
     */
    public static function getShortRole(int $roleId): string
    {
        switch ($roleId) {
            case self::ADMIN_ROLE:
                return self::SHORTCUT_NAME_ROLE_ADMIN;
            case Role::MANAGER_ROLE:
                return self::SHORTCUT_NAME_ROLE_MANAGER;
            case Role::STAFF_ROLE:
                return self::SHORTCUT_NAME_ROLE_STAFF;
            default:
                return self::SHORTCUT_NAME_ROLE_GUESS;
        }
    }

    /**
     * @return HasOne
     */
    public function created_user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by_id');
    }
}
