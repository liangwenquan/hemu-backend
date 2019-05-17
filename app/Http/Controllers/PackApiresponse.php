<?php
/**
 * Created by PhpStorm.
 * User: mytoken
 * Date: 2019-03-15
 * Time: 17:11
 */

namespace App\Http\Controllers;

use Illuminate\Pagination\AbstractPaginator;

trait PackApiresponse
{
    public function pack($errcode, $msg, $data = null)
    {
        $array = [
            'code' => $errcode,
            'message' => $msg
        ];

        if (is_array($data)) $array['data'] = $data;

        return response()->json($array, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function packOk($data = null)
    {
        return $this->pack(0, 'ok', $data);
    }

    /**
     * @deprecated
     * @param $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function packItem($item)
    {
        if (!is_array($item)) {
            $array = array($item->toArray());
        }
        $array = $item;

        return $this->pack(0, 'ok', $array);
    }

    public function packItems($items)
    {
        if ($items instanceof AbstractPaginator) {
            if (func_num_args() == 2 && is_array(func_get_arg(1))) {
                return $this->packPaginator($items, func_get_arg(1));
            }
            return $this->packPaginator($items);
        }

        if (is_array($items)) {
            return $this->pack(0, 'ok', $items);
        }

        return $this->pack(0, 'ok', $items->toArray());
    }

    protected function packPaginator(AbstractPaginator $paginator)
    {
        $response = [
            'code' => 0,
            'message' => 'ok'
        ];

        if (func_num_args() == 2 && is_array(func_get_arg(1))) {
            $response = array_merge($response, func_get_arg(1));
        }

        return response()->json(
            array_merge($response, $paginator->toArray()),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @param $exceptionName
     * @param null $msg
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @deprecated
     */
    public function packApiException($exceptionName, $msg = null)
    {
        $exception = config("api-exception.$exceptionName");
        $errcode = $exception['code'];

        if (!isset($exception['msg']) && is_null($msg))
            throw new \Exception('Api exception msg not set');

        if (isset($exception['msg']))
            $msg = $exception['msg'];

        return $this->pack($errcode, $msg);
    }
}