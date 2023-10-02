<?php

namespace Mume\Product\Services;

use Mume\Core\Common\CommonConst;
use Mume\Core\Services\BaseService;
use Mume\Product\Models\Product;
use Mume\Product\Repositories\Interfaces\ProductRepositoryInterface;
use Mume\Product\Services\Interfaces\ProductServiceInterface;

/**
 * Class ProductService
 *
 * @package Mume\Product\Services
 */
class ProductService extends BaseService implements ProductServiceInterface
{
    /**
     * @var bool
     */
    protected bool $hasUpload = true;

    /**
     * @var array|array[]
     */
    protected array $uploadOptions = [
        'path' => Product::PRODUCT_IMAGE_UPLOAD_DIR . CommonConst::DIRECTORY_SEPARATOR. '$id' . CommonConst::DIRECTORY_SEPARATOR,
        'key' => 'images',
        'is_array_upload' => true
    ];

    /**
     * ProductService constructor.
     *
     * @param  ProductRepositoryInterface  $repository
     */
    public function __construct(
        ProductRepositoryInterface $repository
    ) {
        $this->repository    = $repository;
        parent::__construct($this->repository);
    }
}
