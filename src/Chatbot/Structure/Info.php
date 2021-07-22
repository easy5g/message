<?php
/**
 * User: zhouhua
 * Date: 2021/7/8
 * Time: 5:45 下午
 */

namespace Easy5G\Chatbot\Structure;


use Easy5G\Kernel\Support\Collection;

/**
 * Class Info
 * @package Easy5G\Info\Info
 * @property string $serviceDescription
 * @property string[] $category
 * @property string $callBackNumber
 * @property string $provider
 * @property float $longitude
 * @property float $latitude
 * @property string $themeColour
 * @property string $serviceWebsite
 * @property string $emailAddress
 * @property string $backgroundImage
 * @property string $address
 * @property string $cssStyle
 *
 * @property string $accessNo
 * @property string $domain
 * @property string $serviceName
 * @property string $serviceIcon
 * @property string $TCPage
 * @property string $SMSNumber
 * @property bool $verified
 * @property string $authName
 * @property string $authExpires
 * @property string $authOrg
 * @property bool $status
 * @property string $criticalChatbot
 * @property string $url
 * @property int $version
 * @property string $menu
 */
class Info extends Collection
{
    /**
     * @var string[] 可以设置的字段名
     */
    public $setFiledName = [
        'serviceDescription',
        'category',
        'callBackNumber',
        'provider',
        'longitude',
        'latitude',
        'themeColour',
        'serviceWebsite',
        'emailAddress',
        'backgroundImage',
        'address',
        'cssStyle',
    ];

    public function __construct(array $info = [])
    {
        parent::__construct();

        foreach ($info as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * __set
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $method = 'set' . $key;

        if (in_array($key, $this->setFiledName)) {
            $this->{$method}($value);
        }
    }

    /**
     * set
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * add
     * @param $key
     * @param $value
     */
    public function add($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * assignment
     * @param array $info
     * @return Info
     */
    public function assignment(array $info)
    {
        $this->items = $info;

        return $this;
    }

    /**
     * setServiceDescription
     * @param $value
     * @return bool
     */
    public function setServiceDescription($value)
    {
        if (!$this->strLimit($value, 500)) {
            return false;
        }

        parent::set('serviceDescription', $value);

        return true;
    }

    /**
     * setCategory
     * @param $value
     * @return bool
     */
    public function setCategory($value)
    {
        if (is_string($value)) {
            if (strlen($value) > 50) {
                return false;
            }

            $value = [$value];
        } elseif (is_array($value)) {
            if (count($value) > 15) {
                return false;
            }

            foreach ($value as $val) {
                if (!$this->strLimit($val, 50)) {
                    return false;
                }
            }
        } else {
            return false;
        }

        parent::set('category', $value);

        return true;
    }

    /**
     * setCallBackNumber
     * @param $value
     * @return bool
     */
    public function setCallBackNumber($value)
    {
        if (!$this->strLimit($value, 21)) {
            return false;
        }

        parent::set('callBackNumber', $value);

        return true;
    }

    /**
     * setProvider
     * @param $value
     * @return bool
     */
    public function setProvider($value)
    {
        if (!$this->strLimit($value, 100)) {
            return false;
        }

        parent::set('provider', $value);

        return true;
    }

    /**
     * setLongitude
     * @param $value
     * @return bool
     */
    public function setLongitude($value)
    {
        if (!is_float($value)) {
            return false;
        }

        parent::set('longitude', $value);

        return true;
    }

    /**
     * setLatitude
     * @param $value
     * @return bool
     */
    public function setLatitude($value)
    {
        if (!is_float($value)) {
            return false;
        }

        parent::set('latitude', $value);

        return true;
    }

    /**
     * setThemeColour
     * @param $value
     * @return bool
     */
    public function setThemeColour($value)
    {
        if (!$this->strLimit($value, 20)) {
            return false;
        }

        parent::set('themeColour', $value);

        return true;
    }

    /**
     * setServiceWebsite
     * @param $value
     * @return bool
     */
    public function setServiceWebsite($value)
    {
        if (!$this->strLimit($value, 150)) {
            return false;
        }

        parent::set('serviceWebsite', $value);

        return true;
    }

    /**
     * setEmailAddress
     * @param $value
     * @return bool
     */
    public function setEmailAddress($value)
    {
        if (!$this->strLimit($value, 50)) {
            return false;
        }

        parent::set('emailAddress', $value);

        return true;
    }

    /**
     * setBackgroundImage
     * @param $value
     * @return bool
     */
    public function setBackgroundImage($value)
    {
        if (!$this->strLimit($value, 150)) {
            return false;
        }

        parent::set('backgroundImage', $value);

        return true;
    }

    /**
     * setAddress
     * @param $value
     * @return bool
     */
    public function setAddress($value)
    {
        if (!$this->strLimit($value, 200)) {
            return false;
        }

        parent::set('address', $value);

        return true;
    }

    /**
     * setCssStyle
     * @param $value
     * @return bool
     */
    public function setCssStyle($value)
    {
        if (!$this->strLimit($value, 150)) {
            return false;
        }

        parent::set('cssStyle', $value);

        return true;
    }

    /**
     * strLimit
     * @param $value
     * @param $len
     * @return bool
     */
    protected function strLimit($value, $len)
    {
        return is_string($value) && strlen($value) < $len;
    }

    /**
     * getMenu
     * @return string
     */
    public function getMenu()
    {
        return $this->menu;
    }
}