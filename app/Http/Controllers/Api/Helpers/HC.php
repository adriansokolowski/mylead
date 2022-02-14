<?php
namespace App\Http\Controllers\Api\Helpers;

use App\Http\Controllers\Controller;

class HC extends Controller/* Helper Controller */
{
    /**
     * @param $model
     * @param array $whereIsModel
     * @param array $whereToCreateOrUpdate
     * @return mixed
     */
    public static function createOrUpdateModel($model, array $whereIsModel = [], array $whereToCreateOrUpdate = [])
    {
        $isModel = $model::where($whereIsModel)->first();
        if (!$isModel) {
            try {
                $model::create($whereToCreateOrUpdate);
            } catch (\Exception $e) {
                return self::rErrorTrueDebugInfo(null, [], null, $e);
            }
            return self::rSuccess(null, [], 'create');
        } else {
            try {
                $model->where($whereIsModel)->update($whereToCreateOrUpdate);
            } catch (\Exception $e) {
                return self::rErrorTrueDebugInfo(null, [], null, $e);
            }
            return self::rSuccess(null, [], 'update');
        }

        return self::rErrorTrue();
    }

    /**
     * @param string $msg
     * @param array $data
     * @param string $flag
     * @param array $info
     * @param int $status
     * @return mixed
     */
    public static function rError($msg = null, $status = 200, $data = [], $flag = null, $info = [])
    {
        return response()->json([
            'error' => true,
            'msg' => $msg,
            'data' => $data,
            'flag' => $flag,
            'info' => $info,
        ], $status, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    /**
     * @param string $msg
     * @param array $data
     * @param string $flag
     * @param array $info
     * @param int $status
     * @return mixed
     */
    public static function rSuccess($msg = null, $data = [], $flag = null, $info = [], $status = 200)
    {
        return response()->json([
            'error' => false,
            'msg' => $msg,
            'data' => $data,
            'flag' => $flag,
            'info' => $info,
        ], $status, [], JSON_PRESERVE_ZERO_FRACTION );
    }

    /**
     * @param string $msg
     * @param array $data
     * @param string $flag
     * @param array $info
     * @param int $status
     * @return mixed
     */
    public static function rErrorTrue($msg = null, $data = [], $flag = null, $info = [], $status = 200)
    {
        return response()->json([
            'error' => true,
            'msg' => $msg,
            'data' => $data,
            'flag' => $flag,
            'info' => $info,
        ], $status, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    /**
     * @param string $msg
     * @param array $data
     * @param string $flag
     * @param array $info
     * @param int $status
     * @return mixed
     */
    public static function rErrorTrueDebugInfo($msg = null, $data = [], $flag = null, $info = [], $status = 200)
    {
        if (empty($flag)) {$flag = 'error-true-debug-info';}
        if (!env('APP_DEBUG_INFO', false)) {$info = null;}
        return response()->json([
            'error' => true,
            'msg' => $msg,
            'data' => $data,
            'flag' => $flag,
            'info' => $info,
        ], $status, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    /**
     * @param string $msg
     * @param array $data
     * @param int $status
     * @return mixed
     */
    public static function responseErrorDebugInfo($msg = null, $data = [], $status = 200)
    {
        if (empty($flag)) {$flag = 'error-true-debug-info';}
        if (!env('APP_DEBUG_INFO', false)) {$info = null;}
        return response()->json([
            'error' => true,
            'msg' => $msg,
            'data' => $data,
        ], $status, [], JSON_INVALID_UTF8_SUBSTITUTE);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array ['limit' => $limit, 'skip' => $skip ]
     */
    public static function rQuerySkipAndLimit($page = 1, $limit = 10)
    {
        $tmpPage = 1;
        if ($page >= 1) {$tmpPage = $page;}

        $tmpLimit = 10;
        if ($limit >= 1) {$tmpLimit = $limit;}
        $tmpSkip = ($tmpPage - 1) * $tmpLimit;

        return ['limit' => $tmpLimit, 'skip' => $tmpSkip];
    }

    public static function getRequirementsRequest(array $data = [])
    {
        $tmpData = [];
        foreach ($data as $item) {
            if (is_string($item)) {
                $tmpString = explode('|', $item);
                foreach ($tmpString as $s) {
                    $tmpData[] = $s;
                }
            }
            if (is_array($item)) {
                $tmpData[] = $item;
            }
        }
        return $tmpData;
    }
}
