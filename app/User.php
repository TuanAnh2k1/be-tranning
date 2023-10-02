<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    const StatusLock = 0;
    const StatusActive = 1;
    const FolderAvatar = 'user';
    const Male = 'nam';
    const Female = 'nu';

    protected $table = 'userdb';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = ['id', 'email', 'password', 'username', 'name', 'birthday', 'gender', 'created_at', 'updated_at', 'status', 'image'];

    protected $attributes = [
        'status' => true
    ];
}
