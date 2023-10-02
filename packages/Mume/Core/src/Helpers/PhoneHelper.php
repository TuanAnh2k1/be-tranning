<?php

namespace Mume\Core\Helpers;

/**
 * Class PhoneHelper
 *
 * @package Mume\Core\Helpers
 */
class PhoneHelper
{
    public const START_WITH_ZERO              = '0';

    public const START_WITH_COUNTRY_CODE      = '+84';

    public const START_WITH_SPECIAL_CHARACTER = '(+84)';

    /**
     * Trả về số điện thoại sau khi format
     *
     * @param $phone
     *
     * @return string
     */
    public static function resFormatPhone($phone): string
    {
        $firstCharacter = substr($phone, 0, 1);
        switch ($firstCharacter) {
            case self::START_WITH_ZERO:
            {
                return $phone;
            }
            case substr(self::START_WITH_COUNTRY_CODE, 0, 1):
            {
                $replace = substr($phone, 0, strlen(self::START_WITH_COUNTRY_CODE));
                if ($replace == self::START_WITH_SPECIAL_CHARACTER) {
                    return str_replace($replace, '0', $phone);
                }

                return $phone;
            }
            case substr(self::START_WITH_SPECIAL_CHARACTER, 0, 1):
            {
                $replace = substr($phone, 0, strlen(self::START_WITH_SPECIAL_CHARACTER));
                if ($replace == self::START_WITH_SPECIAL_CHARACTER) {
                    return str_replace($replace, '0', $phone);
                }

                return $phone;
            }
            default:
            {
                return is_numeric($firstCharacter) ? '0' . $phone : $phone;
            }
        }
    }
}
