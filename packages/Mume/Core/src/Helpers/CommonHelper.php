<?php

namespace Mume\Core\Helpers;

use Exception;
use Illuminate\Support\Str;
use Mume\Core\Common\DateConst;
use Mume\Core\Common\SDBStatusCode;
use Mume\Core\Dao\SDB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Mume\Core\Entities\DataResultCollection;

class CommonHelper
{
    public static function CommonLog($message)
    {
        //Logging
        if (env('APP_DEBUG') == true) {
            abort($message);
        } else {
            Log::error($message);
        }
    }

    public static function getDefaultStorageDiskName()
    {
        return env("STORAGE_DISK_DEFAULT", 'public');
    }

    //get Image Src
    public static function getImageSrc($image, $type = 'default')
    {
        if ($image == null && $image == '') {
            switch ($type) {
                case 'store':
                    $src = url('/')."/common_images/no-store.png";
                    break;
                case 'avatar':
                    $src = url('/')."/common_images/no-avatar.png";
                    break;
                default:
                    $src = url('/')."/common_images/no-image.png";
                    break;
            }
        } else {
            $diskLocalName = CommonHelper::getDefaultStorageDiskName();
            $src           = Storage::disk($diskLocalName)->url($image);
        }
        return $src;
    }

    //get Image Url
    public static function getImageUrl($imageUri, $diskLocalName = "public"): string
    {
        return Storage::disk($diskLocalName)->url($imageUri);
    }

    //get role name
    public static function getRoleName($name)
    {
        return trans("label.".str_replace(' ', '_', strtolower($name)));
    }

    /**
     * @param  string|null  $routerName
     *
     * @return ModuleInfo
     */
    public static function getModuleInfo(string $routerName = null): ModuleInfo
    {
        $result = new ModuleInfo();
        try {
            $currentRoute = $routerName ? Route::getRoutes()->getByName($routerName) : Route::getCurrentRoute();
            if ($currentRoute != null) {
                $currentActionInfo = $currentRoute->getAction();
                $module = strtolower(trim(str_replace('App\\', '', $currentActionInfo['namespace']), '\\'));
                $module = explode("\\", $module)[0];
                $_action = isset($currentActionInfo['controller']) ? explode('@', $currentActionInfo['controller']) : [];
                $_namespaces_chunks = isset($_action[0]) ? explode('\\', $_action[0]) : [];
                $controllers = strtolower(end($_namespaces_chunks));
                $action = strtolower(end($_action));
                $screenCode = $currentActionInfo['namespace'] . "\\" . $controllers . "\\" . $action;

                $result->module = $module;
                $result->controller = $controllers;
                $result->action = $action;
                $result->screenCode = $screenCode;
            }
        } catch (Exception $ex) {
            //Dont handler here...
        }

        return $result;
    }

    public static function getModuleInfoByMultiRouter($routeName): array
    {
        $result = [];
        foreach ($routeName as $item) {
            $currentRoute = Route::getRoutes()->getByName($item);
            if ($currentRoute != null) {
                $curentActionInfo = $currentRoute->getAction();
                $module = strtolower(trim(str_replace('App\\', '', $curentActionInfo['namespace']), '\\'));
                $module = explode("\\", $module)[0];
                $_action = isset($curentActionInfo['controller']) ? explode('@', $curentActionInfo['controller']) : [];
                $_namespaces_chunks = isset($_action[0]) ? explode('\\', $_action[0]) : [];
                $controllers = strtolower(end($_namespaces_chunks));
                $action = strtolower(end($_action));
                $screenCode = $curentActionInfo['namespace'] . "\\" . $controllers . "\\" . $action;

                array_push($result, ['module' => $module, 'controller' => $controllers, 'action' => $action, 'screenCode' => $screenCode, 'routeName' => $item]);
            }
        }
        return $result;
    }

    public static function getExcelTemplatePath(): string
    {
        return base_path() . '/resources/export_templates/';
    }

    public static function getOrderEventName($storeId, $orderChannel): string
    {
        $hash = md5($storeId);
        return $hash."_".$orderChannel;
    }

    public static function getClientChannelName($access_token, $eventName): string
    {
        return $access_token."_".$eventName;
    }

    public static function getSecretIdStore($storeId): string
    {
        return md5($storeId);
    }

    public static function existsStore($storeId): bool
    {
        $store = SDB::table('store_store')->where('id', $storeId)->first();
        if (!empty($store)) {
            return true;
        }
        return false;
    }

    public static function changeTitle($str, $strSymbol='_', $case=MB_CASE_LOWER)
    {// MB_CASE_UPPER / MB_CASE_TITLE / MB_CASE_LOWER
        $str=trim($str);
        if ($str=="") {
            return "";
        }
        $str =str_replace('"', '', $str);
        $str =str_replace("'", '', $str);
        $str = self::stripUnicode($str);
        $str = mb_convert_case($str, $case, 'utf-8');
        return preg_replace('/[\W|_]+/', $strSymbol, $str);
    }

    public static function stripUnicode($str)
    {
        if (!$str) {
            return '';
        }
        //$str = str_replace($a, $b, $str);
        $unicode = [
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ|å|ä|æ|ā|ą|ǻ|ǎ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ|Å|Ä|Æ|Ā|Ą|Ǻ|Ǎ',
            'ae'=>'ǽ',
            'AE'=>'Ǽ',
            'c'=>'ć|ç|ĉ|ċ|č',
            'C'=>'Ć|Ĉ|Ĉ|Ċ|Č',
            'd'=>'đ|ď',
            'D'=>'Đ|Ď',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|ë|ē|ĕ|ę|ė',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ|Ë|Ē|Ĕ|Ę|Ė',
            'f'=>'ƒ',
            'F'=>'',
            'g'=>'ĝ|ğ|ġ|ģ',
            'G'=>'Ĝ|Ğ|Ġ|Ģ',
            'h'=>'ĥ|ħ',
            'H'=>'Ĥ|Ħ',
            'i'=>'í|ì|ỉ|ĩ|ị|î|ï|ī|ĭ|ǐ|į|ı',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị|Î|Ï|Ī|Ĭ|Ǐ|Į|İ',
            'ij'=>'ĳ',
            'IJ'=>'Ĳ',
            'j'=>'ĵ',
            'J'=>'Ĵ',
            'k'=>'ķ',
            'K'=>'Ķ',
            'l'=>'ĺ|ļ|ľ|ŀ|ł',
            'L'=>'Ĺ|Ļ|Ľ|Ŀ|Ł',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|ö|ø|ǿ|ǒ|ō|ŏ|ő',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ|Ö|Ø|Ǿ|Ǒ|Ō|Ŏ|Ő',
            'Oe'=>'œ',
            'OE'=>'Œ',
            'n'=>'ñ|ń|ņ|ň|ŉ',
            'N'=>'Ñ|Ń|Ņ|Ň',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|û|ū|ŭ|ü|ů|ű|ų|ǔ|ǖ|ǘ|ǚ|ǜ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự|Û|Ū|Ŭ|Ü|Ů|Ű|Ų|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ',
            's'=>'ŕ|ŗ|ř',
            'R'=>'Ŕ|Ŗ|Ř',
            's'=>'ß|ſ|ś|ŝ|ş|š',
            'S'=>'Ś|Ŝ|Ş|Š',
            't'=>'ţ|ť|ŧ',
            'T'=>'Ţ|Ť|Ŧ',
            'w'=>'ŵ',
            'W'=>'Ŵ',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ|ÿ|ŷ',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ|Ÿ|Ŷ',
            'z'=>'ź|ż|ž',
            'Z'=>'Ź|Ż|Ž',
        ];
        foreach ($unicode as $khongdau=>$codau) {
            $arr=explode("|", $codau);
            $str = str_replace($arr, $khongdau, $str);
        }
        return $str;
    }

    //Convert to json
    public static function toJson($obj)
    {
        return json_encode($obj);
    }

    public static function isJSON($string): bool
    {
        return is_string($string) && is_array(json_decode($string, true));
    }

    public static function getDateFormat($date)
    {
        return date("H:i", strtotime($date));
    }

    public static function convertDateTimeFormat($date, $format=null)
    {
        if ($format==null) {
            $date = date_create($date);
            $date = date_format($date, DateConst::DATE_FORMAT_SER);
        } else {
            $date = date_create($date);
            $date = date_format($date, $format);
        }
        return $date;
    }

    //get status name of food
    public static function getFoodStatusName($status)
    {
        if ($status === FoodStatusValue::FoodStatusDelete) {
            $status_name = trans("frontend.food_order.delete");
        } else {
            switch ($status) {
                case FoodStatusValue::Waiting :
                    $status_name = trans("frontend.food_order.waiting");
                    break;
                case FoodStatusValue::Process:
                    $status_name = trans("frontend.food_order.cooking");
                    break;
                case FoodStatusValue::Done:
                    $status_name = trans("frontend.food_order.done");
                    break;
                default:
                    $status_name = trans("frontend.food_order.new");
                    break;
            }
        }
        return $status_name;
    }

    //get status name of order
    public static function getOrderStatusName($status)
    {
        switch ($status) {
            case 1:
                $status_name = trans("frontend.order.process");
                break;
            case 2:
                $status_name = trans("frontend.order.done");
                break;
            case 3:
                $status_name = trans("frontend.order.paid");
                break;
            default:
                $status_name = trans("frontend.order.waiting");
                break;
        }
        return $status_name;
    }

    public static function flatten($array, $prefix = '')
    {
        $delimiter = ".";
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = $result + self::flatten($value, $prefix . $key . $delimiter);
            } else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }

    /**
     * @param $input
     * @return array
     */
    public static function array_non_empty_items($input)
    {
        // If it is an element, then just return it
        if (!is_array($input)) {
            return $input;
        }
        $non_empty_items = [];

        foreach ($input as $key => $value) {
            // Ignore empty cells
            if ((is_array($value) && !empty($value)) || (!is_array($value) && $value)) {
                // Use recursion to evaluate cells
                $non_empty_items[$key] = self::array_non_empty_items($value);
                if (empty($non_empty_items[$key])) {
                    unset($non_empty_items[$key]);
                }
            }
        }

        // Finally return the array without empty items
        return $non_empty_items;
    }

    /**
     * @param $fileList
     * @param $diskName //Disk name in config/filesystem
     * @param $subFolder //Subfolder
     * @param $option //option for cloud upload
     * @return DataResultCollection
     */
    public static function uploadFile($fileList, $diskName, $subFolder, $option): DataResultCollection
    {
        $result = new DataResultCollection();
        $result->status = SDBStatusCode::OK;
        $result->data = [];
        //NOTE : This will store file to path with: root path has config in config/filesystems.php, sub folder is $subFolder
        if (is_array($fileList) && !empty($fileList)) {
            foreach ($fileList as $item) {
                $path = Storage::disk($diskName)->put($subFolder, $item, $option);
                $fileInfor = [
                    'client_file_name' => $item->getClientOriginalName(),
                    'uri' => $path,
                    'url' => Storage::disk($diskName)->url($path),
                ];
                $result->data[] = $fileInfor;
            }
        }
        return $result;
    }

    /**
     * @param $diskName
     * @param $filePath
     * @return DataResultCollection
     */
    public static function deleteFile($diskName, $filePath): DataResultCollection
    {
        $result = new DataResultCollection();
        $result->status = SDBStatusCode::OK;
        $result->data = [];
        Storage::disk($diskName)->delete($filePath);
        return $result;
    }

    /**
     * @param $arrayDeviveToken array[string] device token or only one device token as a string
     * @param $title
     * @param $body
     * @param $data             array(key=>value)
     *
     *@author thanhnv
     */
    public static function pushNotification(array $arrayDeviveToken, $title, $body, array $data): DataResultCollection
    {
        $result = new DataResultCollection();
        try {
            if (!empty($arrayDeviveToken)) {
                $optionBuilder = new OptionsBuilder();
                $optionBuilder->setTimeToLive(60 * 20);

                $notificationBuilder = new PayloadNotificationBuilder($title);
                $notificationBuilder->setBody($body)
                    ->setSound('default');

                $dataBuilder = new PayloadDataBuilder();
                $dataBuilder->addData($data);

                $option = $optionBuilder->build();
                $notification = $notificationBuilder->build();
                $data = $dataBuilder->build();

                FCM::sendTo($arrayDeviveToken, $option, $notification, $data);
            }
            $result->status = SDBStatusCode::OK;
        } catch (\Exception $e) {
            $result->status = SDBStatusCode::Excep;
            $result->message = $e->getMessage();
            Log::error($e->getMessage());
        }
        return $result;
    }

    /**
     * Common log Exception
     *
     * @param \Exception $exception
     * @param bool $returnMsg
     * @return string
     */
    public static function logException(\Exception $exception, $returnMsg = true)
    {
        $msg = $exception->getMessage() . ' on ' . $exception->getFile() . ' Line ' . $exception->getLine();
        Log::error($msg);

        if ($returnMsg) {
            return $msg;
        }
    }

    /**
     * @param $filename ex: a.pdf
     * @return mixed
     */
    public static function getStringName($filename)
    {
        $info = pathinfo($filename);
        return array_get($info, 'filename');
    }

    public static function getExceptionError(\Exception $e)
    {
        if (env('APP_DEBUG')==true) {
            return $e->getMessage()." in line ".$e->getLine()."-".$e->getFile()."\n".$e->getTraceAsString();
        } else {
            return "Has error";
        }
    }

    /**
     * @param $filePath
     * @param $fileContent
     * @param  string  $permission
     *
     * @return bool
     * @throws \Exception
     */
    public static function putToS3($filePath, $fileContent, string $permission = 'public'): bool
    {
        return Storage::disk('s3')->put($filePath, $fileContent, $permission);
    }

    /**
     * @param $filePath
     *
     * @return bool
     */
    public static function deleteFileS3($filePath): bool
    {
        return Storage::disk('s3')->delete($filePath);
    }

    public static function getListFileOffline($folderPath, $extension)
    {
        $list = null;
        if (is_dir($folderPath)) {
            $list = array_slice(preg_grep('~\.('.$extension.')$~', scandir($folderPath)), 0);
        } else {
            Log::error('Path not exists ...');
        }
        return $list;
    }

    public static function curl($url, $isJson = false)
    {
        $referer = "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate,sdch");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Requested-With: XMLHttpRequest']);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        $response = curl_exec($ch);

        if ($isJson) {
            return json_decode($response, true);
        }

        return $response;
    }

    public static function getStoreUrl($store_id=1)
    {
        $storeUrl = SDB::table("store_store")
            ->where("id", $store_id)
            ->select("name_title")
            ->get();
        return $storeUrl[0]->name_title;
    }

    public static function deleteFood($orderId, $orderDetailId, $from)
    {
        //delete food item
        SDB::table('store_order_detail')
            ->where('id', $orderDetailId)
            ->delete();
        //get new list food items of order
        $arrOrderDetail = SDB::table('store_order_detail as detail')
            ->join("store_entities as food", "food.id", "=", "detail.entities_id")
            ->join('store_order_detail_status as status', 'status.value', '=', 'detail.status')
            ->where('order_id', $orderId)
            ->groupBy("detail.status")
            ->selectRaw("count(detail.status) as num_status,detail.status")
            ->pluck('num_status', 'status');
        if (isset($arrOrderDetail[FoodStatusValue::NoDone])|| count($arrOrderDetail)==0) {
            SDB::table('store_order')->where('id', $orderId)->update(['status'=>OrderStatusValue::NoDone]);
            $order["status"]      = OrderStatusValue::NoDone;
            $order["status_name"] = CommonHelper::getOrderStatusName(OrderStatusValue::NoDone);
        } elseif (isset($arrOrderDetail[FoodStatusValue::Process])) {
            SDB::table('store_order')->where('id', $orderId)->update(['status'=>OrderStatusValue::Process]);
            $order["status"]      = OrderStatusValue::Process;
            $order["status_name"] = CommonHelper::getOrderStatusName(OrderStatusValue::Process);
        } else {
            SDB::table('store_order')->where('id', $orderId)->update(['status'=>OrderStatusValue::Done]);
            $order["status"]      = OrderStatusValue::Done;
            $order["status_name"] = CommonHelper::getOrderStatusName(OrderStatusValue::Done);
        }
        //get access token and orderId
        $arrOrder       = SDB::table('store_order as order')
            ->join('store_order_status as status', 'status.value', '=', 'order.status')
            ->join("store_location as table", 'table.id', '=', 'order.location_id')
            ->where('order.id', $orderId)
            ->select('order.*', 'status.name as status_name', 'table.name as location_name')
            ->first();
        $order["store_id"] = $arrOrder->store_id;
        $order["location_id"] = $arrOrder->location_id;
        $order["location_name"] = $arrOrder->location_name;
        $order["detail"] = [
             "id"               => $orderDetailId,
             "status"           => FoodStatusValue::FoodStatusDelete,
             "food_status_name" => CommonHelper::getFoodStatusName(FoodStatusValue::FoodStatusDelete),
         ];
        $order["id"] = $orderId;
        $order["access_token"] = $arrOrder->access_token;
        $order["from"] = $from;
        $order["action"] = OrderStatusValue::DELETE_FOOD;
        //call event send to Order
        FoodJob::dispatch($order);
        return $order;
    }

    public static function groupBy($array, $key)
    {
        $return = [];
        $array = (array) $array;
        foreach ($array as $val) {
            $val = (array) $val;
            $return[$val[$key]][] = $val;
        }
        return $return;
    }

    public static function getPrice($price)
    {
        return number_format($price);
    }

    public static function getDataType($type)
    {
        return trans("backend.foodType.".$type);
    }

    public static function uuid(): string
    {
        return Str::uuid()->toString();
    }
}
