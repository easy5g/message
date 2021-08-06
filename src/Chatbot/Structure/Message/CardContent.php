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
class CardContent extends Collection
{
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

        return $this->call($key,$value);
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
    public function call($name, $value)
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
        parent::set('media.mediaUrl', $url);

        return $this;
    }

    /**
     * setMediaContentType
     * @param string $type
     * @return CardContent
     */
    public function setMediaContentType(string $type)
    {
        parent::set('media.mediaContentType', $type);

        return $this;
    }

    /**
     * setMediaFileSize
     * @param int $size
     * @return CardContent
     */
    public function setMediaFileSize(int $size)
    {
        parent::set('media.mediaFileSize', $size);

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

        parent::set('media.height', $height);

        return $this;
    }

    /**
     * setThumbnailUrl
     * @param string $url
     * @return CardContent
     */
    public function setThumbnailUrl(string $url)
    {
        parent::set('media.thumbnailUrl', $url);

        return $this;
    }

    /**
     * setThumbnailContentType
     * @param string $type
     * @return CardContent
     */
    public function setThumbnailContentType(string $type)
    {
        parent::set('media.thumbnailContentType', $type);

        return $this;
    }

    /**
     * setThumbnailFileSize
     * @param int $size
     * @return CardContent
     */
    public function setThumbnailFileSize(int $size)
    {
        parent::set('media.thumbnailFileSize', $size);

        return $this;
    }

    /**
     * setContentDescription
     * @param string $contentDescription
     * @return $this
     */
    public function setContentDescription(string $contentDescription)
    {
        parent::set('media.contentDescription', $contentDescription);

        return $this;
    }

    /**
     * setTitle
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title)
    {
        parent::set('title', $this);

        return $this;
    }

    /**
     * setDescription
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        parent::set('description', $description);

        return $this;
    }

    /**
     * setSuggestions
     * @param Menu $suggestions
     * @return $this
     */
    public function setSuggestions(Menu $suggestions)
    {
        parent::set('suggestions', $suggestions);

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
            !$this->has('media') &&
            !$this->has('title') &&
            !$this->has('description')
        ) {
            throw new CardException('Media, title, description must have at least one item');
        }

        if ($this->has('media')) {
            if (!$this->has('media.mediaUrl')) {
                throw new CardException('Media url must fill in');
            }

            if (!$this->has('media.mediaContentType')) {
                throw new CardException('Media content type must fill in');
            }

            if (!$this->has('media.mediaFileSize')) {
                throw new CardException('Media file size must fill in');
            }

            if (!$this->has('media.height')) {
                throw new CardException('Height must fill in');
            }

            if ($this->has('media.thumbnailUrl')) {
                if (!$this->has('media.thumbnailContentType')) {
                    throw new CardException('When thumbnail url exists, thumbnail content type must be filled in');
                }

                if (!$this->has('media.thumbnailFileSize')) {
                    throw new CardException('When thumbnail url exists, thumbnail file size must be filled in');
                }
            } else {
                $this->forget('media.thumbnailContentType');

                $this->forget('media.thumbnailFileSize');
            }
        }

        $cardContent = parent::all();

        if (isset($cardContent['suggestions'])) {
            $cardContent['suggestions'] = $this->suggestions->toArray();
        }

        return $cardContent;
    }
}