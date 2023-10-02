<?php

use Illuminate\Support\Str;

if (!function_exists('common_first_character')) {
    /**
     * Trả về ký tự đầu tiên cảu từ đầu tiên và cuối trong chuỗi
     *
     * @param string $str
     *
     * @return string
     */
    function common_first_character(string $str): string
    {
        $str = ucfirst(trim(strtoupper($str)));
        $str = explode(' ', $str);
        $strFirst = current($str);
        $strLast = last($str);

        return mb_substr($strFirst, 0, 1, 'utf8') . mb_substr($strLast, 0, 1, 'utf8');
    }
}

if (!function_exists('common_limit_string')) {
    /**
     * Trả về string đã bị giới hạn số ký tự
     *
     * @param string $str
     * @param int $limit
     *
     * @return string|null
     */
    function common_limit_string(string $str = '', int $limit = 0): ?string
    {
        return $str ? mb_substr($str, 0, $limit, 'UTF-8') : null;
    }
}

if (!function_exists('common_check_limit_show_more_string')) {
    /**
     * Kiểm tra độ dài string truyền vào có lớn hơn giới hạn
     *
     * @param string $str
     * @param int $limit
     *
     * @return bool
     */
    function common_check_limit_show_more_string(string $str = '', int $limit = 0): bool
    {
        return strlen($str) > $limit;
    }
}

if (!function_exists('common_search_string_in_array')) {
    /**
     * @param string $str
     * @param $contains
     *
     * @return bool
     */
    function common_search_string_in_array(string $str, $contains): bool
    {
        return Str::contains($str, $contains);
    }
}
