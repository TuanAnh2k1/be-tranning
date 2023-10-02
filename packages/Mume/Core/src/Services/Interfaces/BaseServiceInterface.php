<?php

namespace Mume\Core\Services\Interfaces;

/**
 * Interface BaseServiceInterface
 *
 * @package Mume\Core\Services\Interfaces
 */
interface BaseServiceInterface
{
    /**
     * Thêm record mới
     *
     * @param array $data Dữ liệu để tạo record
     *
     * @return mixed
     */
    public function create(array $data);

    /**
     * Cập nhật một record
     *
     * @param integer $id   ID của record cần cập nhật
     * @param array   $data Dữ liệu cần cập nhật
     *
     * @return mixed
     */
    public function update(int $id, array $data);


    /**
     * Xóa một record
     *
     * @param int|array $ids ID của record cần xóa
     *
     * @return bool
     */
    public function delete($ids): bool;

}
