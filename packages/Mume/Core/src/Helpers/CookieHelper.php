<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

if (!function_exists('common_get_cookie')) {
    /**
     * Nếu $key null thì sẽ trả về tất cả cookie
     * Nếu $key != null thì sẽ trả về cookie tương ứng
     *
     * @param null $key Key của cookie
     *
     * @return array|string|null
     */
    function common_get_cookie($key = null)
    {
        if ($key) {
            return Cookie::get($key);
        } else {
            return Cookie::get();
        }
    }
}

if (!function_exists('common_get_diff_for_human')) {
    /**
     * Trả về thời gian so sánh ngày hiện tại với ngày truyền vào bằng ngôn ngữ
     *
     * @param $date
     *
     * @return string
     */
    function common_get_diff_for_human($date): string
    {
        Carbon::setLocale('vi');
        $date = Carbon::parse($date);
        $now = Carbon::now();
        return $date->diffForHumans($now, true);
    }
}

if (!function_exists('common_diff_in_day')) {
    /**
     * Trả về diff date trong 2 mốc thời gian truyền vào
     *
     * @param $dateTo
     * @param null $dateFrom
     *
     * @return int
     */
    function common_diff_in_day($dateTo, $dateFrom = null): int
    {
        $date = Carbon::parse($dateTo);
        $now = $dateFrom ?: Carbon::now();
        return $date->diffInDays($now);
    }
}
