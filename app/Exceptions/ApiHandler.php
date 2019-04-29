<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * @SuppressWarnings(PHPMD)
 */
trait ApiHandler
{
    public function renderError(Request $request, Exception $e)
    {
        $statusCode = 200;
        $msg = method_exists($e, 'getResponseMessage') ? $e->getResponseMessage() : $this->msg('ServerError');
        $errcode = method_exists($e, 'getErrcode') ? $e->getErrcode() : $this->errcode('ServerError');

        if (method_exists($e, 'getCode') && $e->getCode()) {
            $errcode = $e->getCode();
        }

        if ($e instanceof NotFoundHttpException) {
            $msg = $this->msg('EndPointNotFound');
            $errcode = 40400;
        } elseif ($e instanceof UnauthorizedHttpException || $e instanceof AccessDeniedHttpException) {
            $errcode = $this->errcode('AccessDenied');
            $msg = $this->msg('AccessDenied');
        } elseif ($e instanceof ModelNotFoundException) {
            $msg = method_exists($e, 'getResponseMessage') ? $e->getResponseMessage() : 'Record not found';
            $errcode = method_exists($e, 'getErrcode') ? $e->getErrcode() : 40401;
            $statusCode = 200;
        } elseif ($e instanceof ValidationException) {
            $msgs = $e->validator->errors()->all();

            $msg = '';

            if (count($msgs) == 1) {
                $msg = $msgs[0];
            } else {
                foreach ($msgs as $m) {
                    $msg .= $m . "\n";
                }
            }

            $statusCode = 200;
            $errcode = 42200;
        } elseif ($e instanceof BizException) {
            $msg = $e->getMessage();
            $errcode = $e->getCode();
        } elseif ($e instanceof ProgrammingException) {
            $msg = '哎呀，小囧出错了～';
            $errcode = 50001;
        } else {
            $msg = $e->getMessage();
            $errcode = 42000;
        }

        return $this->packError($statusCode, $errcode, $msg);
    }

    public function isApiCall(Request $request)
    {
        // admin ajax requests
        if ($request->header('X-CSRF-TOKEN')) {
            return true;
        }

        //return $request->wantsJson();
        return strpos($request->getUri(), 'api') !== false;
    }

    public function packError($statusCode, $errcode, $msg)
    {
        $responseJson = [
            'errcode' => $errcode,
            'msg'     => $msg
        ];

        return response()->json($responseJson, $statusCode, [], JSON_UNESCAPED_UNICODE);
    }

    protected function errcode($string)
    {
        $config = config('api-exception');
        return $config[$string]['errcode'];
    }

    protected function msg($string)
    {
        $config = config('api-exception');
        return $config[$string]['msg'];
    }
}
