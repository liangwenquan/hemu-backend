<?php

namespace App\Http\Controllers\Platform;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use zgldh\QiniuStorage\QiniuStorage;
use  App\Http\Controllers\ApiController;

class CommonController extends ApiController
{
    public function uploadFile(Request $request)
    {
        // 判断是否有文件上传
        if ($request->hasFile('file')) {
            // 获取文件,file对应的是前端表单上传input的name
            $file = $request->file('file');

            $disk = QiniuStorage::disk('qiniu');

            // 重命名文件
            $fileName = md5($file->getClientOriginalName().time().rand()).'.'.$file->getClientOriginalExtension();

            // 上传到七牛
            $bool = $disk->put($fileName,file_get_contents($file->getRealPath()));

            // 判断是否上传成功

            if ($bool) {

                $path = $disk->downloadUrl($fileName);

                return $this->packOk([
                    'full_path' => $path,
                    'name' => $fileName
                ]);
            }

            return $this->pack('400400', '上传文件失败');

        }

        return $this->pack('400400', '没有上传文件');
    }
}
