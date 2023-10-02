<?php

namespace Mume\Product\Repositories;

use Mume\Core\Repositories\BaseRepository;
use Mume\Product\Models\Product;
use Mume\Product\Repositories\Interfaces\ProductRepositoryInterface;

/**
 * Class ProductRepository
 *
 * @package Mume\Product\Repositories
 */
class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * @var array|string[]
     */
    protected array $searchKeys = ['name', 'description'];

    /**
     * @var array|string[]
     */
    protected array $supportedFilteringColumns = ['status', 'is_active'];

    /**
     * ProductRepository constructor.
     *
     * @param  Product  $model
     */
    public function __construct(
        Product $model
    ) {
        $this->model = $model->whereNull('deleted_at');
    }
}
