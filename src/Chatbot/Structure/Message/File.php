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

        if (count($data) > 2) {
            throw new InvalidArgumentException('Only two files can be sent');
        }

        foreach ($data as $datum) {
            if (empty($datum['url'])) {
                throw new InvalidArgumentException('Missing URL parameter');
            }
        }

        $this->contentText = $data;
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
}