<?php

use Illuminate\Http\Response;

if (!function_exists('common_format_size_unit')) {
    /**
     * @param $bytes
     *
     * @return string
     */
    function common_format_size_unit($bytes): string
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes .= ' bytes';
        } elseif ($bytes == 1) {
            $bytes .= ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

if (!function_exists('common_get_file_size')) {
    /**
     * @param $url
     *
     * @return mixed|null
     */
    function common_get_file_size($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($httpResponseCode == Response::HTTP_OK) ? $fileSize : null;
    }
}

if (!function_exists('common_get_file_extension_from_url')) {
    /**
     * @param $url
     *
     * @return array|string|string[]
     */
    function common_get_file_extension_from_url($url)
    {
        $path = parse_url($url, PHP_URL_PATH);

        return pathinfo($path, PATHINFO_EXTENSION);
    }
}

if (!function_exists('common_get_file_size_from_base_64')) {
    /**
     * @param $base64
     *
     * @return int
     */
    function common_get_file_size_from_base_64($base64): int
    {
        return (int) (strlen(rtrim($base64, '=')) * 0.75);
    }
}

if (!function_exists('common_get_base_64_info')) {
    /**
     * @param $base64
     *
     * @return array
     */
    function common_get_base_64_info($base64): array
    {
        try {
            $mime_type = mime_content_type($base64);
            $info = explode('/', $mime_type);
            return [
                'size' => (int) (strlen(rtrim($base64, '=')) * 0.75),
                'mime_type' => $mime_type,
                'file_type' => $info[0],
                'extension' => $info[1],
            ];
        } catch (Exception $e) {
            return [];
        }
    }
}
