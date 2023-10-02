<?php

namespace Mume\Core\Helpers;

use Illuminate\Http\JsonResponse;
use Mume\Core\Entities\DataResultCollection;

class ResponseHelper
{
    public static function JsonDataResult(DataResultCollection $data): JsonResponse
    {
        return response()->json($data);
    }
}
