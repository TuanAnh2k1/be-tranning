<?php

namespace Mume\Core\Helpers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Mume\Core\Models\Role;

class AuthHelper
{
    /**
     * Trả về thông tin người dùng đăng nhập
     *
     * @return Authenticatable|null
     */
    public static function loggedInUser():?Authenticatable
    {
        if (!Auth::check()) {
            return null;
        }

        return Auth::user();
    }

    /**
     * Trả về id người dùng đăng nhập
     *
     * @return int|null
     */
    public static function loggedInUserId():?int
    {
        if (!$user = self::loggedInUser()) return null;

        return $user->id;
    }

    /**
     * Trả về thông tin role của người dùng đăng nhập
     *
     * @return Role|null
     */
    public static function loggedInUserRole():?Role
    {
        $user = self::loggedInUser();
        if (!$user) return null;

        return $user->role;
    }

    /**
     * @return bool
     */
    public static function isAdminRole():bool
    {
        $role = self::loggedInUserRole();
        if (!$role) return false;

        return $role->id === Role::ADMIN_ROLE;
    }

    /**
     * @return bool
     */
    public static function isManagerRole():bool
    {
        $role = self::loggedInUserRole();
        if (!$role) return false;

        return $role->id === Role::MANAGER_ROLE;
    }

    /**
     * @return bool
     */
    public static function isStaffRole():bool
    {
        $role = self::loggedInUserRole();
        if (!$role) return false;

        return $role->id === Role::STAFF_ROLE;
    }

    /**
     * @return bool
     */
    public static function isGuessRole():bool
    {
        $role = self::loggedInUserRole();
        if (!$role) return true;

        return false;
    }
}
