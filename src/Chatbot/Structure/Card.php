<?php
/**
 * User: zhouhua
 * Date: 2021/7/29
 * Time: 2:26 下午
 */

namespace Easy5G\Chatbot\Structure;


use Easy5G\Kernel\Contracts\MessageInterface;
use Easy5G\Kernel\Exceptions\CardException;
use Easy5G\Kernel\Support\Collection;

class Card implements MessageInterface
{
    use MessageTrait {
        MessageTrait::getContentType as traitGetContentType;
        MessageTrait::getUTText as traitGetUTText;
    }

    protected $contentType = 'application/vnd.gsma.botmessage.v1.0+json';
    protected $contentEncoding;
    protected $contentText;

    /** @var Menu */
    protected $suggestions;
    /** @var MessageInterface */
    public $fallback;

    /** @var Collection */
    protected $layout;

    /** @var Collection[] */
    protected $content;

    /***
     * Card constructor.
     * @param array $content
     * @param string $encode
     * @throws CardException
     */
    public function __construct($content = [], string $encode = 'utf8')
    {
        $this->contentEncoding = $encode;

        if (!empty($content)) {
            $this->parse($content);
        }
    }

    /**
     * prepareContentText
     */
    protected function prepareContentText()
    {
        $cardsNum = count($this->content);

        if ($cardsNum >= 2) {
            $key = 'generalPurposeCardCarousel';
        } else {
            $key = 'generalPurposeCard';
        }

        $this->contentText['message'][$key]['layout'] = $this->layout->all();

        if ($cardsNum >= 2) {
            foreach ($this->content as $card) {
                $cardData = $card->all();

                if (isset($cardData['suggestions']) && $cardData['suggestions'] instanceof Menu) {
                    $cardData['suggestions'] = $cardData['suggestions']->toArray()['suggestions'];
                }

                $this->contentText['message'][$key]['content'][] = $cardData;
            }
        } else {
            $cardData = reset($this->content)->all();

            if (isset($cardData['suggestions']) && $cardData['suggestions'] instanceof Menu) {
                $cardData['suggestions'] = $cardData['suggestions']->toArray()['suggestions'];
            }

            $this->contentText['message'][$key]['content'] = $cardData;
        }
    }

    /**
     * getToHttpData
     * @return string
     */
    public function getToHttpData()
    {
        $this->prepareContentText();

        return json_encode($this->contentText, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * getContentType
     * @param null $ISP
     * @return string
     */
    public function getContentType($ISP = null): string
    {
        $this->prepareContentText();

        return $this->traitGetContentType($ISP);
    }

    /**
     * getUTText
     * @return array
     */
    public function getUTText(): array
    {
        $this->prepareContentText();

        return $this->traitGetUTText();
    }

    /**
     * setLayout
     * @param string|array $key
     * @param null $value
     */
    public function setLayout($key, $value = null)
    {
        if (!isset($this->layout)) {
            $this->layout = new Collection();
        }

        if (is_array($key)) {
            $layout = $key;
        } else {
            $layout = [$key => $value];
        }

        foreach ($layout as $key => $value) {
            $this->layout->set($key, $value);
        }
    }

    /**
     * setContent
     * @param $key
     * @param null $value
     * @param int $index
     */
    public function setContent($key, $value = null, $index = 0)
    {
        if (is_array($key)) {
            $card = $key;

            if (is_numeric($value)) {
                $index = $value;
            }
        } else {
            $card = [$key => $value];
        }

        if (!isset($this->content[$index])) {
            $this->content[$index] = new Collection();
        }

        foreach ($card as $key => $value) {
            $this->content[$index]->set($key, $value);
        }
    }

    /**
     * parse
     * @param $content
     * @return Card
     * @throws CardException
     */
    public function parse($content)
    {
        if (is_array($content)) {
            $contentArr = $content;
        } elseif (is_string($content)) {
            $contentArr = json_decode($content, true);
        } else {
            throw new CardException('Parameters only accept array or string');
        }

        $type = null;

        if (isset($contentArr['message']['generalPurposeCard'])) {
            $contentArr = $contentArr['message']['generalPurposeCard'];

            $type = 'generalPurposeCard';
        } elseif (isset($contentArr['message']['generalPurposeCardCarousel'])) {
            $contentArr = $contentArr['message']['generalPurposeCardCarousel'];

            $type = 'generalPurposeCardCarousel';
        } elseif (isset($contentArr['generalPurposeCard'])) {
            $contentArr = $contentArr['generalPurposeCard'];

            $type = 'generalPurposeCard';
        } elseif (isset($contentArr['generalPurposeCardCarousel'])) {
            $contentArr = $contentArr['generalPurposeCardCarousel'];

            $type = 'generalPurposeCardCarousel';
        } elseif (
            $contentArr === false ||
            $contentArr === null ||
            !isset($contentArr['content'])
        ) {
            throw new CardException('Structural errors');
        }

        if (empty($this->content)) {
            $card = $this;
        } else {
            $card = new self();
        }

        //如果无法主动区分是单卡片还是多卡片怎跟距key判断
        if ($type === null) {
            $type = is_numeric(array_key_first($contentArr['content'])) ? 'generalPurposeCardCarousel' : 'generalPurposeCard';
        }

        isset($contentArr['layout']) && $card->setLayout($contentArr['layout']);

        if ($type === 'generalPurposeCard') {
            $card->setContent($contentArr['content']);
        } else {
            foreach ($contentArr['content'] as $index => $content) {
                $card->setContent($content, $index);
            }
        }

        return $card;
    }
}