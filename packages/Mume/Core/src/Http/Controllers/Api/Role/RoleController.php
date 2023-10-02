<?php

namespace Mume\Core\Http\Controllers\Api\Role;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mume\Core\Http\Controllers\Api\BaseApiController;
use Mume\Core\Http\Transformers\Role\RoleTransformer;
use Mume\Core\Repositories\Interfaces\Role\RoleRepositoryInterface;

/**
 * Class RoleController
 *
 * @package Mume\Core\Http\Controllers\Api\Role
 */
class RoleController extends BaseApiController
{
    /**
     * @var RoleRepositoryInterface
     */
    protected RoleRepositoryInterface $roleRepository;

    /**
     * @param  RoleRepositoryInterface  $roleRepository
     */
    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Danh sách role
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $roles = $this->roleRepository->findByConditions($request->all(), ['created_user']);

            return $this->throwSuccessResponsePagination('', $roles, new RoleTransformer());
        } catch (Exception $e) {
            Log::error(
                'Lỗi khi lấy danh sách role',
                [
                    'line'          => __LINE__,
                    'method'        => __METHOD__,
                    'error_message' => $e->getMessage(),
                    'context'       => [
                        'request' => $request->all(),
                    ],
                ]
            );

            return $this->throwErrorResponse(__('core::api/errors.error_500'));
        }
    }
}
