<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 11:45 上午
 */

namespace Easy5G\Chatbot\Material;


use Easy5G\Kernel\Support\Const5G;

class ChinaUnicom extends Client
{
    use Common;

    protected $uploadUrl = '%s/bot/%s/%s/medias/upload';
    protected $deleteUrl = '%s/bot/%s/%s/medias/delete';
    protected $downloadUrl = '%s/bot/%s/%s/medias/download';

    protected $serviceProvider = Const5G::CU;
    protected $allowTypes = [
        'png','jpg','jpeg','amr','mp3','m4a','mp4','webm'
    ];
}