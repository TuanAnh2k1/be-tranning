<?php

namespace Mume\Core\Http\Controllers\Api\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Mume\Core\Http\Controllers\Api\BaseApiController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LoginController
 *
 * @package Mume\Core\Http\Controllers\Api\Auth
 */
class LoginController extends BaseApiController
{
    public function foo()
    {
        echo "Foo";
    }
    /**
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $username = $request->username;
            $password = $request->password;

            if (!Auth::attempt(['username' => $username, 'password' => $password])) {
                return $this->throwErrorResponse('Sai tên đăng nhập hoặc mật khẩu', Response::HTTP_BAD_REQUEST);
            }

            if (!Auth::user()->is_active) {
                Auth::logout();

                return $this->throwErrorResponse('Tài khoản đang bị khóa, vui lòng liên hệ quản lý của bạn để giải quyết', Response::HTTP_FORBIDDEN);
            }

            $user  = Auth::user();
            $token = $user->createToken("Login by: $username")->plainTextToken;
            $data  = [
                'token'    => $token,
                'username' => $user->username,
                'name'     => $user->name,
            ];

            return $this->throwSuccessResponse('Đăng nhập thành công', $data);
        } catch (Exception $e) {
            Log::error(
                'Lỗi xảy ra khi đăng nhập',
                [
                    'line'          => __LINE__,
                    'method'        => __METHOD__,
                    'error_message' => $e->getMessage(),
                    'context'       => [
                        'request' => $request->all(),
                    ],
                ]
            );

            return $this->throwErrorResponse(__('core::api/errors.error_500'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->throwSuccessResponse('Đăng xuất thành công');
    }
}
