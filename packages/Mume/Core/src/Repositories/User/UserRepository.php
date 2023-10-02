<?php

namespace Mume\Core\Repositories\User;

use Mume\Core\Models\User;
use Mume\Core\Repositories\BaseRepository;
use Mume\Core\Repositories\Interfaces\User\UserRepositoryInterface;

/**
 * Class UserRepository
 *
 * @package Mume\Core\Repositories\User
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @var array|string[]
     */
    protected array $searchKeys = ['username', 'name', 'email', 'phone_number'];

    /**
     * @var array|string[]
     */
    protected array $supportedFilteringColumns = ['is_active'];

    /**
     * UserRepository constructor.
     *
     * @param  User  $model
     */
    public function __construct(User $model)
    {
        $this->model = $model->whereNull('deleted_at');
    }
}
