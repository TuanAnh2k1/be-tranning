<?php

    namespace Mume\Core\Services;

    use Exception;
    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\Hash;
    use Mume\Core\Helpers\ArrayHelper;
    use Mume\Core\Helpers\AuthHelper;
    use Mume\Core\Helpers\DateHelper;
    use Mume\Core\Repositories\Interfaces\BaseRepositoryInterface;
    use Mume\Core\Services\Interfaces\BaseServiceInterface;
    use Mume\Core\Services\Interfaces\Upload\UploadServiceInterface;

    /**
     * Class BaseService
     *
     * @package Mume\Core\Services
     */
    class BaseService implements BaseServiceInterface
    {
        /**
         * @var
         */
        protected $repository;

        /**
         * @var bool
         */
        protected bool $hasUpload = false;

        /**
         * @var array|array[]  [ [uploadPath, uploadKey, isArrayUpload] ]
         */
        protected array $uploadOptions = [['path' => 'upload', 'key' => 'image', 'is_array_upload' => false]];

        /**
         * @var bool
         */
        protected bool $isNullableAuth = false;

        /**
         * @var UploadServiceInterface
         */
        protected UploadServiceInterface $uploadService;

        /**
         * BaseService constructor.
         *
         * @param  BaseRepositoryInterface  $repository
         */
        public function __construct(
            BaseRepositoryInterface $repository
        ) {
            $this->repository = $repository;
            if ($this->hasUpload) {
                $this->uploadService = App::make(UploadServiceInterface::class);
            }
        }

        /**
         * Thêm bản ghi mới
         *
         * @param  array  $data  Dữ liệu để tạo bản ghi
         *
         * @return mixed
         * @throws Exception
         */
        public function create(array $data)
        {
            $normalizedData = $this->_normalizeDataForCreate($data);
            $receipt        = $this->repository->create($normalizedData);

            return $receipt->refresh();
        }

        /**
         * Chuẩn hóa dữ liệu trước khi lưu
         *
         * @param $data
         *
         * @return array
         * @throws Exception
         */
        protected function _normalizeData($data): array
        {
            if (!empty($data['birth_date'])) {
                $data['birth_date'] = DateHelper::parseDateToServerDate($data['birth_date']);
            }

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            if (!$this->hasUpload && !empty($this->uploadOptions)) {
                if (ArrayHelper::isMultiArray($this->uploadOptions)) {
                    foreach ($this->uploadOptions as $option) {
                        $data[$option['key']] = null;
                    }
                } else {
                    $data[$this->uploadOptions['key']] = null;
                }
            }

            if ($this->hasUpload && ArrayHelper::isMultiArray($this->uploadOptions)) {
                foreach ($this->uploadOptions as $option) {
                    $data = $this->normalizeUploadData($option, $data);
                }
            }

            if ($this->hasUpload && !ArrayHelper::isMultiArray($this->uploadOptions)) {
                $data = $this->normalizeUploadData($this->uploadOptions, $data);
            }

            return $data;
        }

        /**
         * Chuẩn hóa dữ liệu trước khi lưu
         *
         * @param $data
         *
         * @return array
         * @throws Exception
         */
        protected function _normalizeDataForCreate($data): array
        {
            $data = $this->_normalizeData($data);
            if (!empty($data['created_by_id'])) {
                return $data;
            }

            if (!$user = AuthHelper::loggedInUser()) {
                throw new Exception('Không xác định được ID của người dùng');
            }

            $data['created_by_id'] = $user->id;

            return $data;
        }

        /**
         * Cập nhật bản ghi
         *
         * @param  integer  $id    ID của bản ghi cần cập nhật
         * @param  array    $data  Dữ liệu cần cập nhật
         *
         * @return mixed
         * @throws Exception
         */
        public function update(int $id, array $data)
        {
            $data['id'] = $id;
            $normalize = $this->_normalizeDataForUpdate($data);

            return $this->repository->update($id, $normalize);
        }

        /**
         * Chuẩn hóa dữ liệu bản ghi trước khi cập nhật
         *
         * @param $data
         *
         * @return array
         * @throws Exception
         */
        protected function _normalizeDataForUpdate($data): array
        {
            $data = $this->_normalizeData($data);
            if (!empty($data['latest_update_by_id'])) {
                return $data;
            }

            if (!$user = AuthHelper::loggedInUser()) {
                throw new Exception('Không xác định được ID của người dùng');
            }

            $data['latest_update_by_id'] = $user->id;

            return $data;
        }

        /**
         * Xóa dữ liệu bản ghi
         *
         * @param  int|array  $ids  ID của bản ghi cần xóa
         *
         * @return bool
         * @throws Exception
         */
        public function delete($ids): bool
        {
            $normalize = $this->_normalizeDataForDelete();

            return $this->repository->update($ids, $normalize);
        }

        /**
         * Chuẩn hóa dữ liệu bản ghi trước khi xóa
         *
         * @return array
         * @throws Exception
         */
        protected function _normalizeDataForDelete(): array
        {
            $data['deleted_at'] = DateHelper::now();
            if (!empty($data['deleted_by_id'])) {
                return $data;
            }

            if (!$user = AuthHelper::loggedInUser()) {
                throw new Exception('Không xác định được ID của người dùng');
            }

            $data['deleted_by_id'] = $user->id;

            return $data;
        }


        /**
         * Lấy đường dẫn upload
         *
         * @param  array  $uploadOption
         * @param  array  $data
         *
         * @return string|null
         */
        protected function getUploadPath(array $uploadOption, array $data): ?string
        {
            if (!$this->hasUpload || empty($uploadOption['path'])) {
                return null;
            }

            if (preg_match_all('/\$(.*?)\//', $uploadOption['path'], $matches)) {
                $keys = $matches[1];
                foreach ($keys as $key) {
                    $pattern = '$' . $key;
                    $value = 'empty';
                    if (!empty($data[$key])) {
                        $value = $data[$key];
                    } else if ($key == 'id') {
                        $value = $this->repository->nextId();
                    }

                    $uploadOption['path'] = str_replace($pattern, $value, $uploadOption['path']);
                }
            }

            return $uploadOption['path'];
        }

        /**
         * Lấy key upload
         *
         * @param  array  $uploadOption
         *
         * @return string|null
         */
        protected function getUploadKey(array $uploadOption): ?string
        {
            if (!$this->hasUpload || empty($uploadOption['key'])) {
                return null;
            }

            return $uploadOption['key'];
        }

        /**
         * Kiểm tra có phải upload array không
         *
         * @param  array  $uploadOption
         *
         * @return bool
         */
        protected function isArrayUpload(array $uploadOption): bool
        {
            if (!$this->hasUpload || empty($uploadOption['is_array_upload'])) {
                return false;
            }

            return $uploadOption['is_array_upload'];
        }

        /**
         * Chuẩn hóa dữ liệu upload
         *
         * @param  array  $uploadOption
         * @param  array  $data
         *
         * @return array
         */
        protected function normalizeUploadData(array $uploadOption, array $data): array
        {
            $uploadPath    = $this->getUploadPath($uploadOption, $data);
            $uploadKey     = $this->getUploadKey($uploadOption);
            $isArrayUpload = $this->isArrayUpload($uploadOption);
            if (!$uploadKey || !$uploadPath || empty($data[$uploadKey])) {
                return $data;
            }
            if ($isArrayUpload) {
                $arrayPath = [];
                foreach ($data[$uploadKey] as $uploadItem) {
                    $arrayPath[] = $this->uploadService->upload($uploadItem, $uploadPath);
                }

                $data[$uploadKey] = $arrayPath;

                return $data;
            }

            $data[$uploadKey] = $this->uploadService->upload($data[$uploadKey], $uploadPath);

            return $data;
        }
    }
