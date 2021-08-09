<?php
/**
 * User: zhouhua
 * Date: 2021/7/29
 * Time: 2:26 下午
 */

namespace Easy5G\Chatbot\Structure\Message;


use Easy5G\Chatbot\Structure\Menu;
use Easy5G\Chatbot\Structure\MessageTrait;
use Easy5G\Kernel\Contracts\MessageInterface;
use Easy5G\Kernel\Exceptions\InvalidArgumentException;
use Easy5G\Kernel\Support\Xml;

class File implements MessageInterface
{
    use MessageTrait;

    protected $contentType = 'application/vnd.gsma.rcs-ft-http';
    protected $contentEncoding;
    protected $contentText;

    /** @var Menu */
    protected $suggestions;

    /** @var MessageInterface */
    public $fallback;

    public function __construct(array $data = [], string $encode = 'utf8')
    {
        if (!empty($data)) {
            $this->checkAndSetContentText($data);
        }

        $this->contentEncoding = $encode;
    }

    /**
     * checkAndSetContentText
     * @param array $data
     */
    protected function checkAndSetContentText(array $data)
    {
        if (isset($data['url'])) {
            $data = [$data];
        }

        $dataNum = count($data);

        if ($dataNum > 2) {
            throw new InvalidArgumentException('Only two files can be sent');
        }

        foreach ($data as $key => $datum) {
            if (empty($datum['url'])) {
                throw new InvalidArgumentException('Missing URL parameter');
            }

            if ($dataNum === 1) {
                if (isset($datum['type']) && $datum['type'] === 'thumbnail') {
                    throw new InvalidArgumentException('Files must exist');
                } else {
                    $data[$key]['type'] = 'file';
                }
            } else {
                if ($datum['type'] === 'file') {
                    $file = $datum;
                } elseif ($datum['type'] === 'thumbnail') {
                    $thumbnail = $datum;
                } else {
                    throw new InvalidArgumentException('Type can only be file and thumbnail');
                }

                if (empty($file) || empty($thumbnail)) {
                    throw new InvalidArgumentException('Files and thumbnails must exist');
                }
            }
        }

        $this->contentText = $data;
    }

    /**
     * setFileUrl
     * @param string $url
     * @return File
     */
    public function setFileUrl(string $url)
    {
        return $this->setData('url', $url);
    }

    /**
     * setFileSize
     * @param int $size
     * @return File
     */
    public function setFileSize($size)
    {
        return $this->setData('fileSize', (int)$size);
    }

    /**
     * setFileContentType
     * @param $contentType
     * @return File
     */
    public function setFileContentType($contentType)
    {
        return $this->setData('contentType', $contentType);
    }

    /**
     * setFileUntil
     * @param $until
     * @return File
     */
    public function setFileUntil($until)
    {
        return $this->setData('until', $until);
    }

    /**
     * setFilename
     * @param $filename
     * @return File
     */
    public function setFilename($filename)
    {
        return $this->setData('fileName', $filename);
    }

    /**
     * setThumbUrl
     * @param string $url
     * @return File
     */
    public function setThumbUrl(string $url)
    {
        return $this->setData('url', $url, 'thumbnail');
    }

    /**
     * setThumbSize
     * @param int $size
     * @return File
     */
    public function setThumbSize(int $size)
    {
        return $this->setData('fileSize', $size, 'thumbnail');
    }

    /**
     * setThumbContentType
     * @param $contentType
     * @return File
     */
    public function setThumbContentType($contentType)
    {
        return $this->setData('contentType', $contentType, 'thumbnail');
    }

    /**
     * setThumbUntil
     * @param $until
     * @return File
     */
    public function setThumbUntil($until)
    {
        return $this->setData('until', $until, 'thumbnail');
    }

    /**
     * setThumbName
     * @param $filename
     * @return File
     */
    public function setThumbName($filename)
    {
        return $this->setData('fileName', $filename, 'thumbnail');
    }

    /**
     * getToHttpData
     * @return string
     */
    public function getToHttpData()
    {
        $fileInfo = [
            'file' => [
                '@attributes' => [
                    'xmlns' => 'urn:gsma:params:xml:ns:rcs:rcs:fthttp'
                ]
            ],
            'file-info' => []
        ];

        foreach ($this->contentText as $value) {
            $tmp = [
                'data' => [
                    '@attributes' => [
                        'url' => $value['url']
                    ]
                ]
            ];

            isset($value['until']) && $tmp['data']['@attributes']['until'] = $value['until'];

            isset($value['type']) && $tmp['@attributes']['type'] = $value['type'];

            isset($value['fileSize']) && $tmp['file-size'] = $value['fileSize'];

            isset($value['contentType']) && $tmp['content-type'] = $value['contentType'];

            isset($value['fileName']) && $tmp['file-name'] = $value['fileName'];

            $fileInfo['file-info'][] = $tmp;
        }

        return Xml::build($fileInfo, ['version' => '1.0', 'encoding' => 'UTF-8']);
    }

    /**
     * setData
     * @param $name
     * @param $value
     * @param string $type
     * @return File
     */
    protected function setData($name, $value, $type = 'file')
    {
        $index = $this->getIndex($type);

        if ($index === null) {
            $this->contentText[] = [
                'type' => $type,
                $name => $value,
            ];
        } else {
            $this->contentText[$index][$name] = $value;
        }

        return $this;
    }

    /**
     * getIndex
     * @param string $type
     * @return int|null
     */
    protected function getIndex(string $type = 'file')
    {
        if (!empty($this->contentText)) {
            foreach ($this->contentText as $index => $value) {
                if ($value['type'] === $type) {
                    return $index;
                }
            }
        }

        return null;
    }
}