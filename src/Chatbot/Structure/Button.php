<?php
/**
 * User: zhouhua
 * Date: 2021/7/21
 * Time: 1:56 下午
 */

namespace Easy5G\Chatbot\Structure;


use Easy5G\Kernel\Exceptions\MenuException;
use Easy5G\Kernel\Support\Collection;

class Button extends Collection
{
    /**
     * create
     * @param array $button
     * @return Button
     */
    public static function raw($button)
    {
        if (is_string($button)) {
            $button  = json_decode($button,true);
        }

        if (!is_array($button)) {
            throw new MenuException('Raw data structural errors');
        }

        return new self($button);
    }

    /**
     * reply
     * @param $display
     * @param $return
     * @return Button
     */
    public static function reply($display, $return)
    {
        return new self([
            'reply' => [
                'displayText' => $display,
                'postback' => [
                    'data' => $return
                ]
            ]
        ]);
    }

    /**
     * action
     * @param $display
     * @param $return
     * @param $actionData
     * @return Button
     */
    public static function action($display, $return, $actionData)
    {
        return new self([
            'action' => $actionData + [
                    'displayText' => $display,
                    'postback' => [
                        'data' => $return
                    ]
                ]
        ]);
    }

    /**
     * url
     * @param $display
     * @param $return
     * @param string $url
     * @param string|null $application
     * @param string|null $viewMode
     * @param string|null $parameters
     * @return Button
     */
    public static function url($display, $return, string $url, string $application, ?string $viewMode = null, ?string $parameters = null)
    {
        $actionData = [
            'url' => $url,
        ];

        $application && $actionData['application'] = $application;
        $viewMode && $actionData['viewMode'] = $viewMode;
        $parameters && $actionData['parameters'] = $parameters;

        return self::action($display, $return, [
            'urlAction' => [
                'openUrl' => $actionData
            ]
        ]);
    }

    /**
     * call
     * @param $display
     * @param $return
     * @param $phoneNumber
     * @param string|null $fallbackUrl
     * @return Button
     */
    public static function call($display, $return, $phoneNumber, ?string $fallbackUrl = null)
    {
        $actionData = [
            'phoneNumber' => $phoneNumber,
        ];

        $fallbackUrl && $actionData['fallbackUrl'] = $fallbackUrl;

        return self::action($display, $return, [
            'dialerAction' => [
                'dialPhoneNumber' => $actionData
            ]
        ]);
    }

    /**
     * videoCall
     * @param $display
     * @param $return
     * @param $phoneNumber
     * @param string|null $fallbackUrl
     * @return Button
     */
    public static function videoCall($display, $return, $phoneNumber, ?string $fallbackUrl = null)
    {
        $actionData = [
            'phoneNumber' => $phoneNumber,
        ];

        $fallbackUrl && $actionData['fallbackUrl'] = $fallbackUrl;

        return self::action($display, $return, [
            'dialerAction' => [
                'dialVideoCall' => $actionData
            ]
        ]);
    }

    /**
     * showLocation
     * @param $display
     * @param $return
     * @param $latitude
     * @param $longitude
     * @param string|null $label
     * @param string|null $query
     * @param string|null $fallbackUrl
     * @return Button
     */
    public static function showLocation($display, $return, $latitude, $longitude, ?string $label = null, ?string $query = null, ?string $fallbackUrl = null)
    {
        $actionData = [
            'location' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ],
        ];

        $label && $actionData['location']['label'] = $label;
        $query && $actionData['location']['query'] = $query;
        $fallbackUrl && $actionData['fallbackUrl'] = $fallbackUrl;

        return self::action($display, $return, [
            'mapAction' => [
                'showLocation' => $actionData
            ]
        ]);
    }

    public static function getLocation($display, $return)
    {
        //todo 文档不完善
    }

    /**
     * createCalendarEvent
     * @param $display
     * @param $return
     * @param string $startTime
     * @param string $endTime
     * @param string $title
     * @param string|null $description
     * @param string|null $fallbackUrl
     * @return Button
     */
    public static function createCalendarEvent($display, $return, string $startTime, string $endTime, string $title, ?string $description = null, ?string $fallbackUrl = null)
    {
        $actionData = [
            'startTime' => $startTime,
            'endTime' => $endTime,
            'title' => $title,
        ];

        $description && $actionData['description'] = $description;
        $fallbackUrl && $actionData['fallbackUrl'] = $fallbackUrl;

        return self::action($display, $return, [
            'calendarAction' => [
                'createCalendarEvent' => $actionData
            ]
        ]);
    }

    public static function sendText($display, $return, $phoneNumber, string $text)
    {
        $actionData = [
            'phoneNumber' => $phoneNumber,
            'text' => $text,
        ];

        return self::action($display, $return, [
            'composeAction' => [
                'composeTextMessage' => $actionData
            ]
        ]);
    }

    public static function sendAudioOrVideo($display, $return)
    {
        //todo 文档不完善
    }

    public static function getTerminalInfo($display, $return)
    {
        //todo 文档不完善
    }
}