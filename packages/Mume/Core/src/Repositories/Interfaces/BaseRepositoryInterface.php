<?php

namespace Mume\Core\Repositories\Interfaces;

/**
 * Interface BaseRepositoryInterface
 *
 * @package Mume\Core\Repositories
 */
interface BaseRepositoryInterface
{
    /**
     * @param            $id
     * @param  string[]  $columns
     *
     * @return mixed
     */
    public function find($id, array $columns = ['*']);

    /**
     * @param  string[]  $columns
     *
     * @return mixed
     */
    public function findAll(array $columns = ['*']);

    /**
     * @param  null      $limit
     * @param  string[]  $columns
     *
     * @return mixed
     */
    public function paginate($limit = null, array $columns = ['*']);

    /**
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * @param  array | int  $ids
     * @param  array        $attributes
     *
     * @return mixed
     */
    public function update($ids, array $attributes);

    /**
     * @param         $id
     * @param  array  $softDeleteData
     * @param  bool   $isSoftDelete
     *
     * @return mixed
     */
    public function delete($id, array $softDeleteData = [], bool $isSoftDelete = true);

    /**
     * @param  int  $id
     *
     * @return mixed
     */
    public function permanentlyDelete(int $id);

    /**
     * Lấy ra danh sách dữ liệu theo người dùng đăng nhập tương ứng
     *
     * @return mixed
     */
    public function findByLoggedInUser();

    /**
     * Lấy ra danh sách theo user id tương ứng
     *
     * @param  int  $userId
     *
     * @return mixed
     */
    public function findByUserId(int $userId);

    /**
     * Tìm kiếm danh sách theo role admin
     *
     * @return mixed
     */
    public function findByAdmin();

    /**
     * Tìm kiếm danh sách theo role manager
     *
     * @param  int  $userId
     *
     * @return mixed
     */
    public function findByManager(int $userId);

    /**
     * Tìm kiếm danh sách theo role nhân viên
     *
     * @param  int  $userId
     *
     * @return mixed
     */
    public function findByStaff(int $userId);

    /**
     * Tìm kiếm danh sách theo role khách hàng
     *
     * @return mixed
     */
    public function findByGuess();

    /**
     * Trả về danh sách theo điều kiện
     *
     * @param  array     $conditions
     * @param  string[]  $relations
     * @param  string[]  $columns
     * @param  int|null  $userId
     *
     * @return mixed
     */
    public function findByConditions(array $conditions = [], array $relations = [], int $userId = null, array $columns = ['*']);

    /**
     * Lấy PK tiếp theo của table
     *
     * @param  string  $key
     *
     * @return int
     */
    public function nextId(string $key = 'id'): int;
}
