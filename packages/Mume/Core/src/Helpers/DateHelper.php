<?php

namespace Mume\Core\Helpers;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class DateHelper
{
    /**
     * @param $date
     *
     * @return string
     */
    public static function parseDateToString($date): string
    {
        return Carbon::parse($date)->toDateTimeString();
    }

    /**
     * @param          $date
     * @param  string  $format
     *
     * @return string
     */
    public static function parseDateToServerDate($date, string $format = 'Y-m-d'): string
    {
        return Carbon::parse($date)->format($format);
    }

    public static function now($format = 'Y-m-d H:i:s'): string
    {
        $now = Carbon::now();

        return $now->format($format);
    }

    public static function timestamp(): int
    {
        return Carbon::now()->getTimestamp();
    }
}

if (!function_exists('common_convert_date_to_string')) {
    /**
     * Trả về ký tự đầu tiên của từ đầu tiên và cuối trong chuỗi
     *
     * @param $dateDiff1
     * @param $dateDiff2
     * @param  int  $isDiffAbsolute
     *
     * @return string
     */
    function common_convert_date_to_string($dateDiff1, $dateDiff2, int $isDiffAbsolute = CarbonInterface::DIFF_ABSOLUTE): string
    {
        Carbon::setLocale('vi');
        $createdAt = Carbon::parse($dateDiff1);
        $now = $dateDiff2 ? Carbon::parse($dateDiff2) : Carbon::now();

        return $createdAt->diffForHumans($now, $isDiffAbsolute);
    }
}

if (!function_exists('common_get_day_of_week_human')) {
    /**
     * Trả về thứ trong tuần dạng ngôn ngữ human
     *
     * @param string $date
     *
     * @return string|null
     */
    function common_get_day_of_week_human(string $date): ?string
    {
        $numberDayOfWeek = Carbon::parse($date)->dayOfWeek;

        switch ($numberDayOfWeek) {
            case 0: return "Chủ nhật";
            case 1: return "Thứ hai";
            case 2: return "Thứ ba";
            case 3: return "Thứ tư";
            case 4: return "Thứ năm";
            case 5: return "Thứ sáu";
            case 6: return "Thứ bảy";
            default: return '';
        }
    }
}

if (!function_exists('common_convert_diff_date')) {
    /**
     * Trả về ký tự đầu tiên cảu từ đầu tiên và cuối trong chuỗi
     *
     * @param int $timestamp
     *
     * @return string
     */
    function common_convert_diff_date(int $timestamp): string
    {
        $dateParse = Carbon::createFromTimestamp(substr((string) $timestamp, 0, -3));
        $hour = Carbon::parse($dateParse)->format("H:i");
        $dateFormat = Carbon::parse($dateParse)->format('d/m/Y');
        $dateAgr = Carbon::parse($dateParse)->format('Y/m/d');
        $now = Carbon::now();
        $dateDiff = Carbon::parse($dateParse)->diffInDays($now);
        if ($dateDiff === 0) {
            return "Hôm nay lúc $hour";
        }

        if ($dateDiff === 1) {
            return "Hôm qua lúc $hour";
        }

        return common_get_day_of_week_human($dateAgr) . "  $dateFormat lúc $hour";
    }
}

if (!function_exists('common_convert_to_server_date_format')) {
    /**
     * Format date về dạng date của server
     *
     * @param string $date
     *
     * @return string
     */
    function common_convert_to_server_date_format(string $date): string
    {
        return date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $date)));
    }
}
