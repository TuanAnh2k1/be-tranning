<?php

namespace Mume\Core\Services\User;

use Mume\Core\Common\CommonConst;
use Mume\Core\Models\User;
use Mume\Core\Repositories\Interfaces\User\UserRepositoryInterface;
use Mume\Core\Services\BaseService;
use Mume\Core\Services\Interfaces\User\UserServiceInterface;

/**
 * Class UserService
 *
 * @package Mume\Core\Services\User
 */
class UserService extends BaseService implements UserServiceInterface
{
    /**
     * @var array|array[]
     */
    protected array $uploadOptions = [
        'path' => User::USER_IMAGE_UPLOAD_DIR . CommonConst::DIRECTORY_SEPARATOR. '$id' . CommonConst::DIRECTORY_SEPARATOR . "avatar",
        'key' => 'avatar',
        'is_array_upload' => false
    ];

    /**
     * @var bool
     */
    protected bool $hasUpload = true;

    /**
     * UserService constructor.
     *
     * @param  UserRepositoryInterface  $repository
     */
    public function __construct(
        UserRepositoryInterface $repository
    ) {
        $this->repository    = $repository;
        parent::__construct($this->repository);
    }

}
