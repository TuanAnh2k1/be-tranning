<?php

namespace Mume\Core\Http\Controllers\Api\User;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Mume\Core\Common\CommonConst;
use Mume\Core\Helpers\AuthHelper;
use Mume\Core\Http\Controllers\Api\BaseApiController;
use Mume\Core\Http\Requests\DeleteBatchRequest;
use Mume\Core\Http\Requests\User\UserPasswordUpdateRequest;
use Mume\Core\Http\Requests\User\UserPostRequest;
use Mume\Core\Http\Requests\User\UserUpdateRequest;
use Mume\Core\Http\Transformers\User\UserTransformer;
use Mume\Core\Repositories\Interfaces\User\UserRepositoryInterface;
use Mume\Core\Services\Interfaces\User\UserServiceInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 *
 * @package Mume\Core\Http\Controllers\Api\User
 */
class UserController extends BaseApiController
{
    /**
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * @var UserServiceInterface
     */
    protected UserServiceInterface $userService;

    /**
     * @param  UserRepositoryInterface  $userRepository
     * @param  UserServiceInterface     $userService
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        UserServiceInterface $userService
    ) {
        $this->userRepository = $userRepository;
        $this->userService    = $userService;
    }

    /**
     * Danh sách người dùng
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $users = $this->userRepository->findByConditions($request->all(), ['parent']);

            return $this->throwSuccessResponsePagination('', $users, new UserTransformer());
        } catch (Exception $e) {
            Log::error(
                'Lỗi khi lấy danh sách người dùng',
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

    /**
     * Thông tin người dùng đăng nhập
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            $user = AuthHelper::loggedInUser();

            return $this->throwSuccessResponse('', $user, new UserTransformer());
        } catch (Exception $e) {
            Log::error(
                'Lỗi khi lấy thông tin người dùng',
                [
                    'line'          => __LINE__,
                    'method'        => __METHOD__,
                    'error_message' => $e->getMessage(),
                ]
            );

            return $this->throwErrorResponse(__('core::api/errors.error_500'));
        }
    }

    /**
     * Thêm người dùng
     *
     * @param  UserPostRequest  $request
     *
     * @return JsonResponse
     */
    public function add(UserPostRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            if (AuthHelper::isGuessRole() || AuthHelper::isStaffRole()) {
                return $this->throwErrorResponse('Bạn không có quyền để tạo tài khoản', Response::HTTP_FORBIDDEN);
            }

            $data = $request->validated();
            $user = $this->userService->create($data);
            DB::commit();

            return $this->throwSuccessResponse('Thêm người dùng thành công', $user, new UserTransformer());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(
                'Lỗi khi thêm người dùng',
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

    /**
     * Chi tiết người dùng
     *
     * @param  int                $id
     *
     * @return JsonResponse
     */
    public function detail(int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            if (!$user = $this->userRepository->find($id)) {
                return $this->throwErrorResponse("Không tìm thấy người dùng có ID = $id trong hệ thống", Response::HTTP_NOT_FOUND);
            }

            return $this->throwSuccessResponse('Thông tin người dùng', $user, new UserTransformer());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(
                'Lỗi khi lấy thông tin người dùng',
                [
                    'line'          => __LINE__,
                    'method'        => __METHOD__,
                    'error_message' => $e->getMessage(),
                    'context'       => [
                        'id' => $id,
                    ],
                ]
            );

            return $this->throwErrorResponse(__('core::api/errors.error_500'));
        }
    }

    /**
     * Cập nhật người dùng
     *
     * @param  UserUpdateRequest  $request
     * @param  int                $id
     *
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            if (!$this->userRepository->find($id)) {
                return $this->throwErrorResponse("Không tìm thấy người dùng có ID = $id trong hệ thống", Response::HTTP_NOT_FOUND);
            }

            $data = $request->validated();
            $user = $this->userService->update($id, $data);
            DB::commit();

            return $this->throwSuccessResponse('Cập nhật người dùng thành công', $user, new UserTransformer());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(
                'Lỗi khi cập nhật người dùng',
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

    /**
     * Cập nhật mật khẩu người dùng
     *
     * @param  UserPasswordUpdateRequest  $request
     * @param  int                        $id
     *
     * @return JsonResponse
     */
    public function updatePassword(UserPasswordUpdateRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            if (!$user = $this->userRepository->find($id)) {
                return $this->throwErrorResponse("Không tìm thấy người dùng có ID = $id trong hệ thống", Response::HTTP_NOT_FOUND);
            }

            $data = $request->validated();
            if (!Hash::check($data['old_password'], $user->password)) {
                return $this->throwErrorResponse('Mật khẩu cũ không chính xác', Response::HTTP_BAD_REQUEST);
            }

            $data['password'] = $data['new_password'];
            $user             = $this->userService->update($id, $data);
            DB::commit();

            return $this->throwSuccessResponse('Cập nhật mật khẩu thành công', $user, new UserTransformer());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(
                'Lỗi khi cập nhật mật khẩu người dùng',
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

    /**
     * Xóa người dùng
     *
     * @param  DeleteBatchRequest  $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteBatchRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data   = $request->validated();
            $userId = AuthHelper::loggedInUser()->id;
            if (empty($ids = explode(CommonConst::COMMA_SEPARATOR, $data['ids']))) {
                return $this->throwErrorResponse('Không có thông tin người dùng cần xóa', Response::HTTP_NOT_FOUND);
            }

            if (in_array($userId, $ids)) {
                return $this->throwErrorResponse('Không thể xóa người dùng đang đăng nhập', Response::HTTP_FORBIDDEN);
            }

            $this->userService->delete($ids);
            DB::commit();

            return $this->throwSuccessResponse('Xóa người dùng thành công');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(
                'Lỗi khi xóa người dùng',
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
