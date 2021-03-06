<?php
/**
 * User: zhouhua
 * Date: 2021/7/15
 * Time: 5:12 下午
 */

namespace Easy5G\Kernel\Support;

use Easy5G\Kernel\Exceptions\CommonException;
use finfo;
use Psr\Http\Message\ResponseInterface;

/**
 * Class File.
 */
class File
{
    /**
     * MIME mapping.
     *
     * @var array
     */
    protected static $extensionMap = [
        'image/png' => '.png',
        'image/jpeg' => '.jpg',
        'audio/amr' => '.amr',
        'audio/mpeg' => '.mp3',
        'audio/mp4a-latm' => '.m4a',
        'video/mp4' => '.mp4',
        'video/webm' => '.webm',
        'audio/wav' => '.wav',
        'audio/x-ms-wma' => '.wma',
        'video/x-ms-wmv' => '.wmv',
        'application/vnd.rn-realmedia' => '.rm',
        'audio/mid' => '.mid',
        'image/bmp' => '.bmp',
        'image/gif' => '.gif',
        'image/tiff' => '.tiff',
    ];

    /**
     * File header signatures.
     *
     * @var array
     */
    protected static $signatures = [
        'ffd8ff' => '.jpg',
        '424d' => '.bmp',
        '47494638' => '.gif',
        '2f55736572732f6f7665' => '.png',
        '89504e47' => '.png',
        '494433' => '.mp3',
        'fffb' => '.mp3',
        'fff3' => '.mp3',
        '3026b2758e66cf11' => '.wma',
        '52494646' => '.wav',
        '57415645' => '.wav',
        '41564920' => '.avi',
        '000001ba' => '.mpg',
        '000001b3' => '.mpg',
        '2321414d52' => '.amr',
        '25504446' => '.pdf',
    ];

    /**
     * Return steam extension.
     *
     * @param string $stream
     *
     * @return string|false
     */
    public static function getStreamExt($stream)
    {
        $ext = self::getExtBySignature($stream);

        try {
            if (empty($ext) && is_readable($stream)) {
                $stream = file_get_contents($stream);
            }
        } catch (\Exception $e) {
        }

        $fileInfo = new finfo(FILEINFO_MIME);

        $mime = strstr($fileInfo->buffer($stream), ';', true);

        return isset(self::$extensionMap[$mime]) ? self::$extensionMap[$mime] : $ext;
    }

    /**
     * Get file extension by file header signature.
     *
     * @param string $stream
     *
     * @return string
     */
    public static function getExtBySignature($stream)
    {
        $prefix = strval(bin2hex(mb_strcut($stream, 0, 10)));

        foreach (self::$signatures as $signature => $extension) {
            if (0 === strpos($prefix, strval($signature))) {
                return $extension;
            }
        }

        return '';
    }

    /**
     * readable
     * @param $filepath
     * @return bool
     */
    public static function readable($filepath)
    {
        return is_file($filepath) && is_readable($filepath);
    }

    /**
     * mkdir
     * @param $dir
     * @return bool
     * @throws CommonException
     */
    public static function mkdir($dir)
    {
        if (!is_dir($dir) && @!mkdir($dir, 0755, true)) {
            throw new CommonException('Failed to create folders');
        }

        return true;
    }

    /**
     * saveFileFromResponse
     * @param ResponseInterface $response
     * @param string $resource
     * @param string|null $savePath
     * @param string|null $filename
     * @return string
     * @throws CommonException
     */
    public static function saveFileFromResponse(ResponseInterface $response, string $resource, ?string $savePath, ?string $filename)
    {
        empty($savePath) && $savePath = '/tmp/easy5G';

        $savePath = rtrim($savePath, '/');

        self::mkdir($savePath);

        //没有传入文件名，则从header头获取，未获取到则按地址md5
        if (empty($filename)) {
            if (preg_match('/filename="(?<filename>.*?)"/', $response->getHeaderLine('Content-Disposition'), $match)) {
                $filename = $match['filename'];
            } else {
                $filename = md5($resource);
            }
        }

        $contents = $response->getBody()->getContents();

        //没有后缀则加上后缀
        if (empty(pathinfo($filename, PATHINFO_EXTENSION))) {
            $filename .= File::getStreamExt($contents);
        }

        $filePath = $savePath . '/' . $filename;

        if (@file_put_contents($filePath, $contents) === false) {
            throw new CommonException('Failed to save file path:' . $filePath);
        }

        return $filePath;
    }
}
