<?php

namespace Mume\Core\Http\Transformers\User;

use League\Fractal\TransformerAbstract;
use Mume\Core\Helpers\DateHelper;
use Mume\Core\Models\Role;
use Mume\Core\Models\User;

/**
 * Class UserTransformer
 *
 * @package Mume\Core\Http\Transformers\User
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * Transform data
     *
     * @param  User  $row
     *
     * @return array
     */
    public function transform(User $row): array
    {
        $createdByRoleId = $row->parent ? $row->parent['role_id'] : 0;
        return [
            'id'            => (int) $row['id'],
            'username'      => $row['username'],
            'email'         => $row['email'],
            'name'          => $row['name'],
            'phone_number'  => $row['phone_number'],
            'gender'        => $row['gender'],
            'birth_date'    => DateHelper::parseDateToString($row['birth_date']),
            'avatar'        => $row->getImages('avatar'),
            'description'   => $row['description'],
            'role_id'       => $row['role_id'],
            'shortcut_role' => Role::getShortRole($row['id']),
            'is_active'     => $row['is_active'],
            'created_by'    => [
                'id'            => (int) $row['created_by_id'],
                'shortcut_role' => Role::getShortRole($createdByRoleId),
                'text'          => !empty($row->parent) ? $row->parent->name : null,
            ],
            'created_at'    => DateHelper::parseDateToString($row['created_at']),
        ];
    }
}
