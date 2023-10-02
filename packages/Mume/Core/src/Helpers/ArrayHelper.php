<?php

    namespace Mume\Core\Helpers;

    class ArrayHelper
    {
        /**
         * @param       $array
         * @param       $on
         * @param  int  $order
         *
         * @return array
         */
        public static function arraySort($array, $on, int $order = SORT_ASC): array
        {
            $new_array      = [];
            $sortable_array = [];

            if (count($array) > 0) {
                foreach ($array as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $k2 => $v2) {
                            if ($k2 == $on) {
                                $sortable_array[$k] = $v2;
                            }
                        }
                    } else {
                        $sortable_array[$k] = $v;
                    }
                }

                switch ($order) {
                    case SORT_ASC:
                        asort($sortable_array);
                        break;
                    case SORT_DESC:
                        arsort($sortable_array);
                        break;
                }

                foreach ($sortable_array as $k => $v) {
                    $new_array[$k] = $array[$k];
                }
            }

            return $new_array;
        }

        /**
         * @param  array  $array        Mảng cần tìm kiếm
         * @param  array  $search_list  Mảng điều kiện
         *
         * @return array
         */
        public static function searchByMultiKeys(array $array, array $search_list): array
        {

            // Create the result array
            $result = [];

            // Iterate over each array element
            foreach ($array as $key => $value) {

                // Iterate over each search condition
                foreach ($search_list as $k => $v) {

                    // If the array element does not meet
                    // the search condition then continue
                    // to the next element
                    if (!isset($value[$k]) || $value[$k] != $v) {

                        // Skip two loops
                        continue 2;
                    }
                }

                // Append array element's key to the
                //result array
                $result[$key] = $value;
            }

            // Return result
            return $result;
        }

        /**
         * @param  array  $array  Mảng cần kiểm tra
         *
         * @return bool
         */
        public static function isMultiArray(array $array): bool
        {

            rsort($array);

            return isset($array[0]) && is_array($array[0]);
        }

        /**
         * Tính tổng các array
         *
         * @return array
         */
        public static function sumArray(): array
        {
            $arrays = func_get_args();
            $merged = [];
            foreach ($arrays as $array) {
                foreach ($array as $key => $value) {
                    if (!is_numeric($value)) {
                        continue;
                    }
                    if (!isset($merged[$key])) {
                        $merged[$key] = $value;
                    } else {
                        $merged[$key] += $value;
                    }
                }
            }

            return $merged;
        }
    }

    if (!function_exists('common_array_sorts')) {
        /**
         * @param       $array
         * @param       $on
         * @param  int  $order
         *
         * @return array
         */
        function common_array_sorts($array, $on, int $order = SORT_ASC): array
        {
            $new_array      = [];
            $sortable_array = [];

            if (count($array) > 0) {
                foreach ($array as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $k2 => $v2) {
                            if ($k2 == $on) {
                                $sortable_array[$k] = $v2;
                            }
                        }
                    } else {
                        $sortable_array[$k] = $v;
                    }
                }

                switch ($order) {
                    case SORT_ASC:
                        asort($sortable_array);
                        break;
                    case SORT_DESC:
                        arsort($sortable_array);
                        break;
                }

                foreach ($sortable_array as $k => $v) {
                    $new_array[$k] = $array[$k];
                }
            }

            return $new_array;
        }
    }

    if (!function_exists('common_search_by_multi_keys')) {
        /**
         * @param  array  $array        Mảng cần tìm kiếm
         * @param  array  $search_list  Mảng điều kiện
         *
         * @return array
         */
        function common_search_by_multi_keys(array $array, array $search_list): array
        {

            // Create the result array
            $result = [];

            // Iterate over each array element
            foreach ($array as $key => $value) {

                // Iterate over each search condition
                foreach ($search_list as $k => $v) {

                    // If the array element does not meet
                    // the search condition then continue
                    // to the next element
                    if (!isset($value[$k]) || $value[$k] != $v) {

                        // Skip two loops
                        continue 2;
                    }
                }

                // Append array element's key to the
                //result array
                $result[$key] = $value;
            }

            // Return result
            return $result;
        }
    }
