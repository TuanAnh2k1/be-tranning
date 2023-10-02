<?php

namespace Mume\Core\Entities;

class DataResultCollection
{
    public $data = [];

    public $status;

    public string $msg;

    public function __construct(DataResultCollection $dataResultCollection = null)
    {
        if (isset($dataResultCollection)) {
            $this->data      = $dataResultCollection->data;
            $this->status    = $dataResultCollection->status;
            $this->msg       = $dataResultCollection->msg;
        }
    }

    /**
     * Get first data
     *
     * @return array|mixed
     */
    public function first()
    {
        if (!empty($this->data) && isset($this->data[0])) {
            return $this->data[0];
        } else {
            return [];
        }
    }

    /**
     * @return mixed
     */
    public function dataToArray()
    {
        return json_decode(json_encode($this->data), true);
    }
}
