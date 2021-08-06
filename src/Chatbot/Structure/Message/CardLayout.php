<?php
/**
 * User: zhouhua
 * Date: 2021/7/29
 * Time: 2:26 下午
 */

namespace Easy5G\Chatbot\Structure\Message;


use Easy5G\Kernel\Exceptions\CardException;
use Easy5G\Kernel\Support\Collection;

/**
 * Class CardLayout
 * @package Easy5G\Chatbot\Structure\Message
 * 单卡片支持参数
 * @property $cardOrientation
 * @property $imageAlignment
 * 多卡片支持参数
 * @property $cardWidth
 * 公共参数
 * @property $titleFontStyle
 * @property $descriptionFontStyle
 * @property $style
 */
class CardLayout extends Collection
{
    /**
     * set
     * @param $key
     * @param $value
     * @return CardLayout
     * @throws CardException
     */
    public function set($key, $value)
    {
        $name = 'set' . ucfirst($key);

        if (!method_exists($this, $name)) {
            throw new CardException("Setting {$key} is not allowed");
        }

        return $this->{$name}($value);
    }

    /**
     * setCardOrientation
     * @param string $cardOrientation
     * @return CardLayout
     * @throws CardException
     */
    public function setCardOrientation(string $cardOrientation)
    {
        $cardOrientation = strtoupper($cardOrientation);

        if (!in_array($cardOrientation, ['VERTICAL', 'HORIZONTAL'])) {
            throw new CardException('Card orientation can only be set to vertical or horizontal');
        }

        parent::set('cardOrientation', $cardOrientation);

        return $this;
    }

    /**
     * setImageAlignment
     * @param string $imageAlignment
     * @return CardLayout
     * @throws CardException
     */
    public function setImageAlignment(string $imageAlignment)
    {
        $imageAlignment = strtoupper($imageAlignment);

        if (!in_array($imageAlignment, ['LEFT', 'RIGHT'])) {
            throw new CardException('Image alignment can only be set to left or right');
        }

        parent::set('imageAlignment', $imageAlignment);

        return $this;
    }

    /**
     * setCardWidth
     * @param string $cardWidth
     * @return CardLayout
     * @throws CardException
     */
    public function setCardWidth(string $cardWidth)
    {
        $cardWidth = strtoupper($cardWidth);

        if (!in_array($cardWidth, ['SMALL_WIDTH', 'MEDIUM_WIDTH'])) {
            throw new CardException('Card width can only be set to small_width or medium_width');
        }

        parent::set('cardWidth', $cardWidth);

        return $this;
    }

    /**
     * setTitleFontStyle
     * @param string|array $titleFontStyle
     * @return CardLayout
     */
    public function setTitleFontStyle($titleFontStyle)
    {
        if (is_string($titleFontStyle)) {
            $titleFontStyle = [$titleFontStyle];
        } elseif (!is_array($titleFontStyle)) {
            return $this;
        }

        foreach ($titleFontStyle as $key => &$style) {
            $style = strtolower($style);

            if (!in_array($style, ['italics', 'bold', 'underline'])) {
                unset($titleFontStyle[$key]);
            }
        }

        if (!empty($titleFontStyle)) {
            parent::set('titleFontStyle', $titleFontStyle);
        }

        return $this;
    }

    /**
     * setDescriptionFontStyle
     * @param string|array $descriptionFontStyle
     * @return CardLayout
     */
    public function setDescriptionFontStyle($descriptionFontStyle)
    {
        if (is_string($descriptionFontStyle)) {
            $descriptionFontStyle = [$descriptionFontStyle];
        } elseif (!is_array($descriptionFontStyle)) {
            return $this;
        }

        foreach ($descriptionFontStyle as $key => &$style) {
            $style = strtolower($style);

            if (!in_array($style, ['italics', 'bold', 'underline'])) {
                unset($descriptionFontStyle[$key]);
            }
        }

        if (!empty($descriptionFontStyle)) {
            parent::set('descriptionFontStyle', $descriptionFontStyle);
        }

        return $this;
    }

    /**
     * setStyle
     * @param string $style
     * @return CardLayout
     */
    public function setStyle(string $style)
    {
        parent::set('style', $style);

        return $this;
    }

    /**
     * all
     * @return array
     * @throws CardException
     */
    public function all()
    {
        if (!$this->has('cardOrientation')) {
            throw new CardException('card orientation must be filled');
        }

        if ($this->cardOrientation === 'HORIZONTAL') {
            if (empty($this->imageAlignment))
                throw new CardException('image alignment must be filled when card orientation equals horizontal');
        } else {
            $this->forget('imageAlignment');
        }

        return parent::all();
    }
}