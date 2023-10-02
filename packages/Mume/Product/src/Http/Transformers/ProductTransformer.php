<?php

namespace Mume\Product\Http\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Mume\Product\Models\Product;

/**
 * Class ProductTransformer
 *
 * @package Mume\Product\Http\Transformers
 */
class ProductTransformer extends TransformerAbstract
{
    /**
     * Transform data
     *
     * @param  Product  $row
     *
     * @return array
     */
    public function transform(Product $row): array
    {
        return [
            'id'           => (int) $row['id'],
            'name'         => $row['name'],
            'sku'          => $row['sku'],
            'price'        => (int) $row['price'],
            'images'       => $row->getImages('images'),
            'status'       => $row['status'],
            'description'  => $row['description'],
            'is_active'    => (int) $row['is_active'],
            'category_ids' => $row['category_ids'],
            'created_by'   => [
                'id'   => (int) $row['created_by_id'],
                'text' => !empty($row->parent) ? $row->parent->name : null,
            ],
            'created_at'   => Carbon::parse($row['created_at'])->toDateTimeString(),
        ];
    }
}
