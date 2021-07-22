<?php
/**
 * User: zhouhua
 * Date: 2021/7/20
 * Time: 4:58 下午
 */

namespace Easy5G\Chatbot\Structure;


use Easy5G\Kernel\Contracts\ChatbotMenuInterface;
use Easy5G\Kernel\Exceptions\MenuException;

class Menu implements ChatbotMenuInterface
{
    const FIRST = 1;
    const SECOND = 2;

    protected $currentLevel = self::FIRST;
    protected $suspension;
    protected $buttonNum = 0;
    protected $display;
    protected $buttons = [];

    /**
     * Menu constructor.
     * @param int $level
     * @param string $display
     * @param bool $suspension
     * @throws MenuException
     */
    public function __construct(int $level = self::FIRST, string $display = '', $suspension = false)
    {
        if ($level !== self::FIRST && $level !== self::SECOND) {
            throw new MenuException('Two levels of menu are allowed at most');
        }

        $this->display = $display;

        $this->currentLevel = $level;

        $this->suspension = $suspension;
    }

    /**
     * getDisplay
     * @return string
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * addButton
     * @param Button $button
     * @return Menu
     * @throws MenuException
     */
    public function addButton(Button $button)
    {
        $this->checkButtonNumLimit();

        $this->buttons[] = $button;

        $this->buttonNum++;

        return $this;
    }

    /**
     * addMenu
     * @param string $display
     * @return Menu
     * @throws MenuException
     */
    public function addMenu(string $display)
    {
        if ($this->suspension) {
            throw new MenuException('Suspension menu only supports add button');
        }

        $this->checkButtonNumLimit();

        $secondMenu = new self(self::SECOND, $display);

        $this->buttons[] = $secondMenu;

        $this->buttonNum++;

        return $secondMenu;
    }

    /**
     * parse
     * @param string $json
     * @return Menu
     */
    public function parse(string $json): ChatbotMenuInterface
    {
        $buttonsArr = json_decode($json, true);
        if (isset($buttonsArr['menu']['entries'])) {
            $buttonsArr = $buttonsArr['menu']['entries'];
        } elseif (isset($buttonsArr['entries'])) {
            $buttonsArr = $buttonsArr['entries'];
        } elseif (
            $buttonsArr === false ||
            $buttonsArr === null ||
            !isset($buttonsArr[0]['reply']) && !isset($buttonsArr[0]['menu']) && !isset($buttonsArr[0]['action'])
        ) {
            throw new MenuException('Structural errors');
        }

        $menu = new self();

        foreach ($buttonsArr as $buttonInfo) {
            if (isset($buttonInfo['reply']) || isset($buttonInfo['action'])) {
                $menu->addButton(Button::raw($buttonInfo));
            } elseif (isset($buttonInfo['menu'])) {
                $secondMenu = $menu->addMenu($buttonInfo['menu']['displayText']);

                foreach ($buttonInfo['menu']['entries'] as $secondButton) {
                    $secondMenu->addButton(Button::raw($secondButton));
                }
            } else {
                throw new MenuException('The menu only supports buttons reply,action and second menu');
            }
        }

        return $menu;
    }

    /**
     * toArray
     * @return array
     */
    public function toArray(): array
    {
        if ($this->suspension) {
            $menu['suggestions'] = $this->getButtonsArr($this->buttons);
        } else {
            $menu['menu']['entries'] = $this->getButtonsArr($this->buttons);
        }

        return $menu;
    }

    /**
     * toJson
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * getButtonsArr
     * @param $menus
     * @return array
     */
    protected function getButtonsArr($menus)
    {
        $buttonsArr = [];

        foreach ($menus as $menu) {
            if ($menu instanceof Button) {
                /** @var Button $menu */
                $buttonsArr[] = $menu->toArray();
            } else {
                /** @var Menu $menu */
                $buttonsArr[] = [
                    'menu' => [
                        'displayText' => $menu->display,
                        'entries' => $this->getButtonsArr($menu->buttons)
                    ]
                ];
            }
        }

        return $buttonsArr;
    }

    /**
     * checkButtonNumLimit
     * @throws MenuException
     */
    protected function checkButtonNumLimit()
    {
        if ($this->suspension) {
            if ($this->buttonNum > 11) {
                throw new MenuException('The suspension menu supports up to eleven buttons');
            }
        } else {
            if ($this->currentLevel === self::FIRST && $this->buttonNum > 3) {
                throw new MenuException('The first level menu supports up to three buttons');
            }

            if ($this->currentLevel === self::SECOND && $this->buttonNum > 5) {
                throw new MenuException('The second level menu supports up to five buttons');
            }
        }
    }
}