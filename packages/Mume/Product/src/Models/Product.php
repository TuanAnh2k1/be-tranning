<?php

namespace Mume\Product\Models;

use Mume\Core\Common\CommonConst;
use Mume\Core\Models\Base;

/**
 * Class Product
 *
 * @package Mume\Product\Models
 */
class Product extends Base
{
    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = 'latest_update_at';

    public const PRODUCT_STATUS_IN_STOCK = 1;
    public const PRODUCT_STATUS_OUT_STOCK = 0;

    public const PRODUCT_IMAGE_UPLOAD_DIR = 'products';

    protected $table = 'products';

    protected $attributes = [
        'is_active' => CommonConst::IS_ACTIVE,
    ];

    protected $casts = [
        'images' => 'json',
        'category_ids' => 'json',
    ];

    protected $fillable = [
        'name',
        'sku',
        'price',
        'images',
        'status',
        'description',
        'is_active',
        'category_ids',
        'created_by_id',
        'created_by_name',
        'created_at',
        'latest_update_by_id',
        'latest_update_by_name',
        'latest_update_at',
        'deleted_by_id',
        'deleted_by_name',
        'deleted_at',
    ];
}
