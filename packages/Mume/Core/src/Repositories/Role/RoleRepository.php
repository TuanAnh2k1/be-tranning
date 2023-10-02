<?php

namespace Mume\Core\Repositories\Role;

use Mume\Core\Models\Role;
use Mume\Core\Repositories\BaseRepository;
use Mume\Core\Repositories\Interfaces\Role\RoleRepositoryInterface;
use Mume\Core\Repositories\Interfaces\User\UserRepositoryInterface;

/**
 * Class RoleRepository
 *
 * @package Mume\Core\Repositories\Role
 */
class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    /**
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * @var array|string[]
     */
    protected array $searchKeys = ['name'];

    /**
     * @var array|string[]
     */
    protected array $supportedFilteringColumns = ['is_active'];

    /**
     * RoleRepository constructor.
     *
     * @param  Role  $model
     * @param  UserRepositoryInterface  $userRepository
     */
    public function __construct(
        Role $model,
        UserRepositoryInterface $userRepository
    ) {
        $this->model = $model;
        $this->userRepository = $userRepository;
    }

    public function findByManager(int $userId)
    {
        $excludeRoleIds = [Role::ADMIN_ROLE, Role::MANAGER_ROLE];
        return $this->model->whereNotIn('id', $excludeRoleIds);
    }
}
