<?php
/**
 * User: zhouhua
 * Date: 2021/7/29
 * Time: 2:26 下午
 */

namespace Easy5G\Chatbot\Structure\Message;

use Easy5G\Chatbot\Structure\Menu;
use Easy5G\Kernel\Exceptions\CardException;
use Easy5G\Kernel\Support\Collection;

/**
 * Class CardContent
 * @package Easy5G\Chatbot\Structure\Message
 * @property $media
 * @property $title
 * @property $description
 * @property Menu $suggestions
 */
class CardContent
{
    /**
     * @var Collection
     */
    protected $card;

    public function __construct(array $card = [])
    {
        $this->card = new Collection($card);
    }

    /**
     * __set
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->card->set(...func_get_args());
    }

    /**
     * __get
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->card->get($name);
    }

    /**
     * set
     * @param $key
     * @param $value
     * @return mixed
     * @throws CardException
     */
    public function set($key, $value)
    {
        if (strpos($key, '.') !== false) {
            $key = explode('.', $key, 2)[1];
        }

        return $this->call($key, $value);
    }

    /**
     * setMedia
     * @param $value
     * @return CardContent
     * @throws CardException
     */
    public function setMedia($value)
    {
        if (!is_array($value)) {
            throw new CardException("Media must be an array");
        }

        foreach ($value as $key => $val) {
            $this->call($key, $val);
        }

        return $this;
    }

    /**
     * call
     * @param $name
     * @param $value
     * @return CardContent
     * @throws CardException
     */
    protected function call($name, $value)
    {
        $callbackName = 'set' . ucfirst($name);

        if (!method_exists($this, $callbackName)) {
            throw new CardException("Setting {$name} is not allowed");
        }

        return $this->{$callbackName}($value);
    }

    /**
     * setMediaUrl
     * @param string $url
     * @return CardContent
     */
    public function setMediaUrl(string $url)
    {
        $this->card->set('media.mediaUrl', $url);

        return $this;
    }

    /**
     * setMediaContentType
     * @param string $type
     * @return CardContent
     */
    public function setMediaContentType(string $type)
    {
        $this->card->set('media.mediaContentType', $type);

        return $this;
    }

    /**
     * setMediaFileSize
     * @param $size
     * @return CardContent
     */
    public function setMediaFileSize($size)
    {
        $this->card->set('media.mediaFileSize', (int)$size);

        return $this;
    }

    /**
     * setHeight
     * @param string $height
     * @return $this
     * @throws CardException
     */
    public function setHeight(string $height)
    {
        $height = strtoupper($height);

        if (!in_array($height, ['SHORT_HEIGHT', 'MEDIUM_HEIGHT', 'TALL_HEIGHT'])) {
            throw new CardException('media height can only be set to short_height,medium_height or tall_height');
        }

        $this->card->set('media.height', $height);

        return $this;
    }

    /**
     * setThumbnailUrl
     * @param string $url
     * @return CardContent
     */
    public function setThumbnailUrl(string $url)
    {
        $this->card->set('media.thumbnailUrl', $url);

        return $this;
    }

    /**
     * setThumbnailContentType
     * @param string $type
     * @return CardContent
     */
    public function setThumbnailContentType(string $type)
    {
        $this->card->set('media.thumbnailContentType', $type);

        return $this;
    }

    /**
     * setThumbnailFileSize
     * @param $size
     * @return CardContent
     */
    public function setThumbnailFileSize($size)
    {
        $this->card->set('media.thumbnailFileSize', (int)$size);

        return $this;
    }

    /**
     * setContentDescription
     * @param string $contentDescription
     * @return CardContent
     */
    public function setContentDescription(string $contentDescription)
    {
        $this->card->set('media.contentDescription', $contentDescription);

        return $this;
    }

    /**
     * setTitle
     * @param string $title
     * @return CardContent
     */
    public function setTitle(string $title)
    {
        $this->card->set('title', $title);

        return $this;
    }

    /**
     * setDescription
     * @param string $description
     * @return CardContent
     */
    public function setDescription(string $description)
    {
        $this->card->set('description', $description);

        return $this;
    }

    /**
     * setSuggestions
     * @param Menu $suggestions
     * @return CardContent
     */
    public function setSuggestions(Menu $suggestions)
    {
        $this->card->set('suggestions', $suggestions);

        return $this;
    }

    /**
     * all
     * @return array
     * @throws CardException
     */
    public function all()
    {
        if (
            !$this->card->has('media') &&
            !$this->card->has('title') &&
            !$this->card->has('description')
        ) {
            throw new CardException('Media, title, description must have at least one item');
        }

        if ($this->card->has('media')) {
            if (!$this->card->has('media.mediaUrl')) {
                throw new CardException('Media url must fill in');
            }

            if (!$this->card->has('media.mediaContentType')) {
                throw new CardException('Media content type must fill in');
            }

            if (!$this->card->has('media.mediaFileSize')) {
                throw new CardException('Media file size must fill in');
            }

            if (!$this->card->has('media.height')) {
                throw new CardException('Height must fill in');
            }

            if ($this->card->has('media.thumbnailUrl')) {
                if (!$this->card->has('media.thumbnailContentType')) {
                    throw new CardException('When thumbnail url exists, thumbnail content type must be filled in');
                }

                if (!$this->card->has('media.thumbnailFileSize')) {
                    throw new CardException('When thumbnail url exists, thumbnail file size must be filled in');
                }
            } else {
                $this->card->forget('media.thumbnailContentType');

                $this->card->forget('media.thumbnailFileSize');
            }
        }

        $cardContent = $this->card->all();

        if (isset($cardContent['suggestions'])) {
            $cardContent['suggestions'] = $cardContent['suggestions']->toArray()['suggestions'];
        }

        return $cardContent;
    }
}