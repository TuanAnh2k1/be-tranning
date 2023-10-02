<?php

namespace Mume\Core\Common;

class CommonConst
{
    public const IS_ACTIVE = 1;

    public const IS_NOT_ACTIVE = 0;

    public const MAXIMUM_SIZE_AVATAR = 2048;

    public const MAXIMUM_SIZE_FILE_UPLOAD = 1048576;

    public const VN_PHONE_NUMBER_REGEX = '/(\b((02)+([0-9]{9}))\b|(\b(09|03|07|08|05)+([0-9]{8}))\b)|(\b(090|080|070)+([0-9]{8}))\b/';

    public const GENDER_FEMALE = 0;

    public const GENDER_MALE = 1;

    public const GENDER_OTHER = 2;

    public const UPDATE_METHOD = ['PUT', 'PATCH'];

    public const COMMA_SEPARATOR = ',';

    public const LOCAL_STORAGE = 'public';

    public const DIRECTORY_SEPARATOR = '/';
}
