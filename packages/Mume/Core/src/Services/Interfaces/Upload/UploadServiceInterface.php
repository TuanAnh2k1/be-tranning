<?php

namespace Mume\Core\Services\Interfaces\Upload;

use Illuminate\Http\UploadedFile;
use Mume\Core\Entities\DataResultCollection;

/**
 * Interface UploadServiceInterface
 *
 * @package Mume\Core\Services\Interfaces
 */
interface UploadServiceInterface
{
    /**
     * @param $fileList
     * @param $diskName
     * @param $subFolder
     * @param $option
     *
     * @return DataResultCollection
     */
    public function uploadFile($fileList,$diskName,$subFolder,$option):DataResultCollection;

    /**
     * @param $diskName
     * @param $filePath
     *
     * @return DataResultCollection
     */
    public function deleteFile($diskName,$filePath):DataResultCollection;

    /**
     * Upload
     *
     * @param  string|UploadedFile  $file
     * @param string                $uploadPath
     *
     * @return string|null
     */
    public function upload(string|UploadedFile $file, string $uploadPath): ?string;

    /**
     * Upload file
     *
     * @param UploadedFile $file
     * @param string $uploadPath
     * @param string $fileName
     *
     * @return string|null
     */
    public function uploadFileV2(UploadedFile $file, string $uploadPath, string $fileName): ?string;

    /**
     * Upload file từ base 64
     *
     * @param string $base64File
     * @param string $uploadPath
     * @param string $fileName
     *
     * @return string|null
     */
    public function uploadFileFromBase64(string $base64File, string $uploadPath, string $fileName): ?string;

    /**
     * Upload file from url
     *
     * @param string $fileUrl
     * @param string $uploadPath
     *
     * @return string|null
     */
    public function uploadFileFromUrl(string $fileUrl, string $uploadPath): ?string;

    /**
     * Move file to S3
     *
     * @param string $sourceFilePath Path của file ở dưới local (chứa tên file)
     * @param string $destinationPath Path của file ở trên cloud (chứa tên file)
     *
     * @return boolean
     */
    public function moveFile(string $sourceFilePath, string $destinationPath): bool;

    /**
     * Delete files
     *
     * @param  array  $filePaths
     *
     * @return mixed
     */
    public function deleteFiles(array $filePaths): mixed;

    /**
     * Lấy đường dẫn local đầy đủ của file
     *
     * @param $filePath
     * @return string
     */
    public function getLocalFilePath($filePath): string;

    /**
     * Check local disk
     *
     * @return bool
     */
    public function isLocalDisk(): bool;

    /**
     * Kiểm tra file đã tồn tại trong thư mục tương ứng chưa
     *
     * @param string $path
     *
     * @return bool
     */
    public function isFileExistInPath(string $path):bool;

    /**
     * Static method to return full file url from disk
     *
     * @param string $path
     *
     * @return string|null
     */
    public static function getFileUrl(string $path): ?string;

}
