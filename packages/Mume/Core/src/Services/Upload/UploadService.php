<?php

    namespace Mume\Core\Services\Upload;

    use Exception;
    use Illuminate\Contracts\Filesystem\Filesystem;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Storage;
    use Mume\Core\Common\CommonConst;
    use Mume\Core\Common\SDBStatusCode;
    use Mume\Core\Entities\DataResultCollection;
    use Mume\Core\Helpers\CommonHelper;
    use Mume\Core\Helpers\DateHelper;
    use Mume\Core\Services\Interfaces\Upload\UploadServiceInterface;

    /**
     * Class UploadService
     *
     * @package Mume\Core\Services\Upload
     */
    class UploadService implements UploadServiceInterface
    {
        /**
         * @param $fileList
         * @param $diskName   //Disk name in config/filesystem
         * @param $subFolder  //Subfolder
         * @param $option     //option for cloud upload
         *
         * @return DataResultCollection
         */
        public function uploadFile($fileList, $diskName, $subFolder, $option): DataResultCollection
        {
            $result         = new DataResultCollection();
            $result->status = SDBStatusCode::OK;
            $result->data   = [];
            //NOTE : This will store file to path with: root path has config in config/filesystems.php, sub folder is $subFolder
            if (is_array($fileList) && !empty($fileList)) {
                foreach ($fileList as $item) {
                    $path           = Storage::disk($diskName)->put($subFolder, $item, $option);
                    $fileInfor      = [
                        'client_file_name' => $item->getClientOriginalName(),
                        'uri'              => $path,
                        'url'              => Storage::disk($diskName)->url($path),
                    ];
                    $result->data[] = $fileInfor;
                }
            }
            return $result;
        }

        /**
         * @param $diskName
         * @param $filePath
         *
         * @return DataResultCollection
         */
        public function deleteFile($diskName, $filePath): DataResultCollection
        {
            $result         = new DataResultCollection();
            $result->status = SDBStatusCode::OK;
            $result->data   = [];
            Storage::disk($diskName)->delete($filePath);
            return $result;
        }

        /**
         * @return Filesystem
         */
        private static function storageInstance(): Filesystem
        {
            $diskName = CommonHelper::getDefaultStorageDiskName();

            return Storage::disk($diskName);
        }

        /**
         *
         * @param  string|UploadedFile  $file
         * @param  string               $uploadPath
         *
         * @return string|null
         */
        public function upload(string|UploadedFile $file, string $uploadPath): ?string
        {
            if (is_uploaded_file($file)) {
                $fileName = $file->getClientOriginalName();
                return $this->uploadFileV2($file, $uploadPath, $fileName);
            }

            $isBase64Encode = base64_decode($file, true) === false;
            if (!$isBase64Encode) return null;
            $info = common_get_base_64_info($file);
            if (empty($info)) return null;
            $fileName = CommonHelper::uuid().'_'.DateHelper::timestamp().'.' . $info['extension'];

            return $this->uploadFileFromBase64($file, $uploadPath, $fileName);
        }

        /**
         * Upload file
         *
         * @param  UploadedFile  $file
         * @param  string        $uploadPath
         * @param  string        $fileName
         *
         * @return string|null
         */
        public function uploadFileV2(UploadedFile $file, string $uploadPath, string $fileName): ?string
        {
            try {
                return $this->storageInstance()->putFileAs(
                    $uploadPath,
                    $file,
                    $fileName
                );
            } catch (Exception $e) {
                Log::error(
                    'Lỗi khi upload file',
                    [
                        'line'          => __LINE__,
                        'method'        => __METHOD__,
                        'error_message' => $e->getMessage(),
                        'context'       => [
                            'upload_path' => $uploadPath,
                            'file_name'   => $fileName,
                        ],
                    ]
                );

                return null;
            }
        }

        /**
         * Upload file từ base 64
         *
         * @param  string  $base64File
         * @param  string  $uploadPath
         * @param  string  $fileName
         *
         * @return string|null
         */
        public function uploadFileFromBase64(string $base64File, string $uploadPath, string $fileName): ?string
        {
            if (preg_match_all('/data:(.*?)base64,/', $base64File, $matches)) {
                $base64File = str_replace($matches[0], '', $base64File);
            }

            try {
                $this->storageInstance()->put(
                    $uploadPath.CommonConst::DIRECTORY_SEPARATOR.$fileName,
                    base64_decode($base64File),
                    'public'
                );

                return $uploadPath.CommonConst::DIRECTORY_SEPARATOR.$fileName;
            } catch (Exception $e) {
                Log::error(
                    'Lỗi khi upload file từ base 64',
                    [
                        'line'          => __LINE__,
                        'method'        => __METHOD__,
                        'error_message' => $e->getMessage(),
                        'context'       => [
                            'upload_path' => $uploadPath,
                        ],
                    ]
                );

                return null;
            }
        }

        /**
         * Upload file from url
         *
         * @param  string  $fileUrl
         * @param  string  $uploadPath
         *
         * @return string|null
         */
        public function uploadFileFromUrl(string $fileUrl, string $uploadPath): ?string
        {
            try {
                $file = file_get_contents($fileUrl);
                return $this->storageInstance()->put(
                    $uploadPath,
                    $file
                );
            } catch (Exception $e) {
                Log::error(
                    'Lỗi khi upload file từ url',
                    [
                        'line'          => __LINE__,
                        'method'        => __METHOD__,
                        'error_message' => $e->getMessage(),
                        'context'       => [
                            'upload_path' => $uploadPath,
                            'file_url'    => $fileUrl,
                        ],
                    ]
                );

                return null;
            }
        }

        /**
         * Move file to S3
         *
         * @param  string  $sourceFilePath   Path của file ở dưới local (chứa tên file)
         * @param  string  $destinationPath  Path của file ở trên cloud (chứa tên file)
         *
         * @return boolean
         */
        public function moveFile(string $sourceFilePath, string $destinationPath): bool
        {
            try {
                $result = $this->storageInstance()->put($destinationPath, Storage::disk(CommonConst::LOCAL_STORAGE)->get($sourceFilePath));
                if ($result) {
                    return true;
                }
            } catch (Exception $e) {
                Log::error(
                    'Lỗi khi chuyển file lên S3',
                    [
                        'line'          => __LINE__,
                        'method'        => __METHOD__,
                        'error_message' => $e->getMessage(),
                        'context'       => [
                            'source_path'      => $sourceFilePath,
                            'destination_path' => $destinationPath,
                        ],
                    ]
                );
            }

            return false;
        }

        /**
         * Delete files
         *
         * @param  array  $filePaths
         *
         * @return bool
         */
        public function deleteFiles(array $filePaths): bool
        {
            return Storage::disk(config('reseller.files_driver'))->delete($filePaths);
        }

        /**
         * Static method to return full file url from disk
         *
         * @param  string  $path
         *
         * @return string|null
         */
        public static function getFileUrl(string $path): ?string
        {
            return $path ? self::storageInstance()->url($path) : null;
        }

        /**
         * Lấy đường dẫn local đầy đủ của file
         *
         * @param $filePath
         *
         * @return string
         */
        public function getLocalFilePath($filePath): string
        {
            return Storage::disk(CommonConst::LOCAL_STORAGE)->path($filePath);
        }

        /**
         * Check local disk
         *
         * @return bool
         */
        public function isLocalDisk(): bool
        {
            return config('filesystems.default') == CommonConst::LOCAL_STORAGE;
        }

        /**
         * Kiểm tra file đã tồn tại trong thư mục tương ứng chưa
         *
         * @param  string  $path
         *
         * @return bool
         */
        public function isFileExistInPath(string $path): bool
        {
            return $this->storageInstance()->exists($path);
        }
    }
