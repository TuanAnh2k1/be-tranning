<?php

namespace Mume\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mume\Core\Services\Interfaces\Upload\UploadServiceInterface;

/**
 * Class Base
 *
 * @package Mume\Core\Models
 */
class Base extends Model
{

    /**
     * @var UploadServiceInterface
     */
    protected $uploadService;

    /**
     * User constructor.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->uploadService = app('Mume\Core\Services\Interfaces\Upload\UploadServiceInterface');
    }

    /**
     * Lấy đường dẫn hình ảnh
     *
     * @param $avatarKey
     *
     * @return string|array|null
     */
    public function getImages($avatarKey)
    {
        $avatar = $this[$avatarKey];
        if (empty($avatarKey) || empty($avatar)) return null;
        if (is_array($avatar)) {
            $avatars = [];
            foreach ($avatar as $key) {
                $avatars[] = $this->uploadService::getFileUrl($key);
            }

            return $avatars;
        }

        return $this->uploadService::getFileUrl($avatar);
    }

    /**
     * Lấy thông tin người tạo
     *
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }
}
