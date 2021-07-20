<?php
/**
 * User: zhouhua
 * Date: 2021/7/16
 * Time: 10:30 上午
 */

namespace Easy5G\Kernel\Support;


class Xml
{
    /**
     * parse
     * @param $xml
     * @return array|null
     */
    public static function parse($xml)
    {
        PHP_MAJOR_VERSION < 8 && $backup = libxml_disable_entity_loader(true);

        $result = self::normalize(@simplexml_load_string(self::sanitize($xml), 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_NOCDATA | LIBXML_NOBLANKS));

        PHP_MAJOR_VERSION < 8 && libxml_disable_entity_loader($backup);

        return $result;
    }

    /**
     * XML encode.
     *
     * @param mixed $data
     * @param string $root
     * @param string $item
     * @param string $attr
     * @param string $id
     *
     * @return string
     */
    public static function build(
        $data,
        $statement = 'xml',
        $statementAttr = '',
        $root = 'root',
        $rootAttr = '',
        $defaultItem = 'item'
    )
    {
        $statementAttr = self::parseAttr($statementAttr);
        $rootAttr = self::parseAttr($rootAttr);

        $xml = "<?{$statement}{$statementAttr}?>";
        $xml .= "<{$root}{$rootAttr}>";
        $xml .= self::data2Xml($data, $defaultItem);
        $xml .= "</{$root}>";

        return $xml;
    }

    /**
     * parseAttr
     * @param $attr
     * @return string
     */
    protected static function parseAttr($attr)
    {
        if (is_string($attr)) {
            if (empty($attr)) {
                $attr = '';
            } else {
                $attr = ' ' . $attr;
            }
        } elseif (is_array($attr)) {
            $arrAttr = [];

            foreach ($attr as $key => $value) {
                $arrAttr[] = "{$key}=\"{$value}\"";
            }

            $attr = ' ' . implode(' ', $arrAttr);
        } else {
            $attr = '';
        }

        return $attr;
    }

    /**
     * Build CDATA.
     *
     * @param string $string
     *
     * @return string
     */
    public static function cdata($string)
    {
        return sprintf('<![CDATA[%s]]>', $string);
    }

    /**
     * normalize
     * @param $obj
     * @return array|null
     */
    protected static function normalize($obj)
    {
        $result = null;

        if (is_object($obj)) {
            $obj = (array)$obj;
        }

        if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                $res = self::normalize($value);

                if ('@attributes' === $key) {
                    $result = $res;
                } else {
                    $result[$key] = $res;
                }
            }
        } else {
            $result = $obj;
        }

        return $result;
    }

    /**
     * data2Xml
     * @param $data
     * @param string|null $item
     * @param string $parentKey
     * @return string
     */
    protected static function data2Xml($data, ?string $item = 'item', ?string $parentKey = '')
    {
        $xml = $attr = '';

        foreach ($data as $key => $val) {
            is_object($val) && $val = (array)$val;

            if (empty($parentKey)) {
                if (is_numeric($key)) {
                    $key = $item;
                }

                if (is_array($val)) {
                    $xml .= self::data2Xml($val, $item, $key);
                } else {
                    $xml .= "<{$key}>{$val}</{$key}>";
                }
            } else {
                if (is_numeric($key)) {
                    if (is_array($val)) {
                        foreach ($val as $k => $v) {
                            if (is_numeric($k)) {
                                $k = $item;
                            }

                            if (is_array($v)) {
                                $xml .= "<{$parentKey}>" . self::data2Xml($v, $item, $k) . "</{$parentKey}>";
                            } else {
                                $xml .= "<{$parentKey}><{$k}>{$v}</{$k}></{$parentKey}>";
                            }
                        }
                    } else {
                        $xml .= "<{$parentKey}>{$val}</{$parentKey}>";
                    }
                } else {
                    if (is_array($val)) {
                        $xml .= "<{$parentKey}>" . self::data2Xml($val, $item, $key) . "</{$parentKey}>";
                    } else {
                        $xml .= "<{$parentKey}><{$key}>{$val}</{$key}></{$parentKey}>";
                    }
                }
            }
        }

        return $xml;
    }

    /**
     * sanitize
     * @param $xml
     * @return string|string[]|null
     */
    public static function sanitize($xml)
    {
        return preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]+/u', '', $xml);
    }
}