<?php
/**
 * User: zhouhua
 * Date: 2021/7/16
 * Time: 10:30 上午
 */

namespace Easy5G\Kernel\Support;


use Easy5G\Kernel\Exceptions\InvalidArgumentException;
use SimpleXMLElement;

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

        $xml = @simplexml_load_string(self::sanitize($xml), 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_NOCDATA | LIBXML_NOBLANKS);

        if ($xml === false) {
            return null;
        }

        $result = self::normalize($xml->children());

        PHP_MAJOR_VERSION < 8 && libxml_disable_entity_loader($backup);

        return $result;
    }

    /**
     * XML encode.
     *
     * @param mixed $data
     * @param string|array $statementAttr
     * @return string
     */
    public static function build(
        $data,
        $statementAttr = ''
    )
    {
        $statementAttr = self::parseAttr($statementAttr);

        $xml = "<?xml{$statementAttr}?>";
        $xml .= self::data2Xml($data);

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
     * @param SimpleXMLElement $obj
     * @return array|null
     */
    protected static function normalize($obj)
    {
        $result = $attr = [];

        if ($obj->count() > 0) {
            foreach ($obj as $key => $value) {
                if (!empty($value->attributes())) {
                    $attr = (array)$value->attributes();
                } else {
                    $attr = [];
                }

                $son = self::normalize($value);

                if (empty($attr)) {
                    $tmp = $son;
                } else {
                    $tmp = $attr + (is_array($son) ? $son : [$son]);
                }

                if (isset($result[$key])) {
                    $result[$key] = array_merge([$result[$key]], [$tmp]);
                } else {
                    $result[$key] = $tmp;
                }
            }
        } else {
            $result = (array)$obj;

            unset($result['@attributes']);

            $result = $result ? reset($result) : [];
        }

        return $result;
    }

    /**
     * data2Xml
     * @param $data
     * @param string $parentKey
     * @return string
     * @throws InvalidArgumentException
     */
    protected static function data2Xml($data, $parentKey = '')
    {
        $xml = '';

        foreach ($data as $key => $value) {
            if (isset($value['@attributes'])) {
                $attr = self::parseAttr($value['@attributes']);

                unset($value['@attributes']);
            } else {
                $attr = '';
            }

            if (is_string($value)) {
                $xml .= self::createElement($key, $value, $attr);
            } elseif (count($value) === 1 && is_numeric($k = array_key_last($value)) && is_string($value[$k])) {
                $xml .= self::createElement($key, $value[$k], $attr);
            } else {
                $keys = array_keys($value);

                $isNum = is_numeric(array_key_first($value));

                foreach ($keys as $keyItem) {
                    if (is_numeric($keyItem) !== $isNum) {
                        throw new InvalidArgumentException('Missing child element name');
                    }
                }

                if ($isNum) {
                    $xml .= self::data2Xml($value, $key);
                } else {
                    if (is_numeric($key)) {
                        $key = $parentKey;
                    }

                    $xml .= self::createElement($key, self::data2Xml($value, $key), $attr);
                }
            }
        }

        return $xml;
    }

    /**
     * createElement
     * @param $key
     * @param $value
     * @param $attr
     * @return string
     */
    protected static function createElement($key, $value, $attr)
    {
        if ($value === '') {
            return "<{$key}{$attr}/>";
        }else{
            return "<{$key}{$attr}>{$value}</{$key}>";
        }
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