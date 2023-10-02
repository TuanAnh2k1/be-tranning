<?php

namespace Mume\Product\Http\Controllers\Api;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mume\Core\Common\CommonConst;
use Mume\Core\Helpers\AuthHelper;
use Mume\Core\Http\Controllers\Api\BaseApiController;
use Mume\Core\Http\Requests\DeleteBatchRequest;
use Mume\Product\Http\Requests\ProductPostRequest;
use Mume\Product\Http\Requests\ProductUpdateRequest;
use Mume\Product\Http\Transformers\ProductTransformer;
use Mume\Product\Repositories\Interfaces\ProductRepositoryInterface;
use Mume\Product\Services\Interfaces\ProductServiceInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController
 *
 * @package Mume\Product\Http\Controllers\Api
 */
class ProductController extends BaseApiController
{
    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productTypeRepository;

    /**
     * @var ProductServiceInterface
     */
    protected ProductServiceInterface $productTypeService;

    /**
     * @param  ProductRepositoryInterface  $productTypeRepository
     * @param  ProductServiceInterface     $productTypeService
     */
    public function __construct(
        ProductRepositoryInterface $productTypeRepository,
        ProductServiceInterface $productTypeService
    ) {
        $this->productTypeRepository = $productTypeRepository;
        $this->productTypeService    = $productTypeService;
    }

    /**
     * Danh sách sản phẩm
     *
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $productTypes = $this->productTypeRepository->findByConditions($request->all());

            return $this->throwSuccessResponsePagination('', $productTypes, new ProductTransformer());
        } catch (Exception $e) {
            Log::error(
                'Lỗi khi lấy danh sách sản phẩm',
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
     * Thêm sản phẩm
     *
     * @param  ProductPostRequest  $request
     *
     * @return JsonResponse
     */
    public function add(ProductPostRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            if (AuthHelper::isGuessRole() || AuthHelper::isStaffRole()) {
                return $this->throwErrorResponse('Bạn không có quyền để tạo sản phẩm', Response::HTTP_FORBIDDEN);
            }

            $data    = $request->validated();
            $product = $this->productTypeService->create($data);
            DB::commit();

            return $this->throwSuccessResponse('Thêm sản phẩm thành công', $product, new ProductTransformer());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(
                'Lỗi khi thêm sản phẩm',
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
     * Chi tiết sản phẩm
     *
     * @param  int  $id
     *
     * @return JsonResponse
     */
    public function detail(int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            if (!$product = $this->productTypeRepository->find($id)) {
                return $this->throwErrorResponse("Không tìm thấy sản phẩm có ID = $id trong hệ thống", Response::HTTP_NOT_FOUND);
            }

            DB::commit();

            return $this->throwSuccessResponse('Chi tiết sản phẩm', $product, new ProductTransformer());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(
                'Lỗi khi lấy thông tin sản phẩm',
                [
                    'line'          => __LINE__,
                    'method'        => __METHOD__,
                    'error_message' => $e->getMessage(),
                    'context'       => [
                        'ID' => $id,
                    ],
                ]
            );

            return $this->throwErrorResponse(__('core::api/errors.error_500'));
        }
    }

    /**
     * Cập nhật sản phẩm
     *
     * @param  ProductUpdateRequest  $request
     * @param  int                     $id
     *
     * @return JsonResponse
     */
    public function update(ProductUpdateRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            if (!$this->productTypeRepository->find($id)) {
                return $this->throwErrorResponse("Không tìm thấy sản phẩm có ID = $id trong hệ thống", Response::HTTP_NOT_FOUND);
            }

            $data    = $request->validated();
            $product = $this->productTypeService->update($id, $data);
            DB::commit();

            return $this->throwSuccessResponse('Cập nhật sản phẩm thành công', $product, new ProductTransformer());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(
                'Lỗi khi cập nhật sản phẩm',
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
     * Xóa sản phẩm
     *
     * @param  DeleteBatchRequest  $request
     *
     * @return JsonResponse
     */
    public function delete(DeleteBatchRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if (empty($ids = explode(CommonConst::COMMA_SEPARATOR, $data['ids']))) {
                return $this->throwErrorResponse('Không có thông tin sản phẩm cần xóa', Response::HTTP_NOT_FOUND);
            }

            $this->productTypeService->delete($ids);
            DB::commit();

            return $this->throwSuccessResponse('Xóa sản phẩm thành công');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(
                'Lỗi khi xóa sản phẩm',
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
