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
     * @throws MenuException
     */
    public static function raw($button)
    {
        if (is_string($button)) {
            $button = json_decode($button, true);
        }

        if (!is_array($button)) {
            throw new MenuException('Raw data structural errors');
        }

        return new self($button);
    }

    /**
     * reply
     * @param string $display
     * @param $return
     * @return Button
     */
    public static function reply(string $display, $return = null)
    {
        $reply = [
            'reply' => [
                'displayText' => $display,
            ],
        ];

        $return && $reply['reply']['postback']['data'] = $return;

        return new self($reply);
    }

    /**
     * action
     * @param string $display
     * @param array $actionData
     * @param string|null $return
     * @return Button
     */
    public static function action(string $display, array $actionData, ?string $return = null)
    {
        $action = [
            'action' => $actionData + [
                    'displayText' => $display,
                ]
        ];

        $return && $action['action']['postback']['data'] = $return;

        return new self($action);
    }

    /**
     * url
     * @param $display
     * @param string $url
     * @param string $application
     * @param string|null $return
     * @param string|null $viewMode
     * @param string|null $parameters
     * @return Button
     * @throws MenuException
     */
    public static function url($display, string $url, string $application, ?string $return = null, ?string $viewMode = null, ?string $parameters = null)
    {
        $actionData = [
            'url' => $url,
            'application' => $application
        ];

        if ($application !== 'browser' && $application !== 'webview') {
            throw new MenuException('Application can only be set to browser or webview');
        }

        if ($application === 'webview') {
            if (in_array($viewMode, ["full", "half", "tall"])) {
                $actionData['viewMode'] = $viewMode;
            }

            $parameters && $actionData['parameters'] = $parameters;
        }

        return self::action($display, [
            'urlAction' => [
                'openUrl' => $actionData
            ]
        ], $return);
    }

    /**
     * call
     * @param $display
     * @param string $phoneNumber
     * @param string|null $return
     * @param string|null $fallbackUrl
     * @return Button
     */
    public static function call($display, string $phoneNumber, ?string $return = null, ?string $fallbackUrl = null)
    {
        $actionData = [
            'phoneNumber' => $phoneNumber,
        ];

        $fallbackUrl && $actionData['fallbackUrl'] = $fallbackUrl;

        return self::action($display, [
            'dialerAction' => [
                'dialPhoneNumber' => $actionData
            ]
        ], $return);
    }

    /**
     * videoCall
     * @param $display
     * @param $phoneNumber
     * @param string|null $return
     * @param string|null $fallbackUrl
     * @return Button
     */
    public static function videoCall($display, $phoneNumber, ?string $return = null, ?string $fallbackUrl = null)
    {
        $actionData = [
            'phoneNumber' => $phoneNumber,
        ];

        $fallbackUrl && $actionData['fallbackUrl'] = $fallbackUrl;

        return self::action($display, [
            'dialerAction' => [
                'dialVideoCall' => $actionData
            ]
        ], $return);
    }

    /**
     * showLocation
     * @param $display
     * @param null $latitude
     * @param null $longitude
     * @param string|null $query
     * @param string|null $return
     * @param string|null $label
     * @param string|null $fallbackUrl
     * @return Button
     * @throws MenuException
     */
    public static function showLocation($display, $latitude = null, $longitude = null, ?string $query = null, ?string $return = null, ?string $label = null, ?string $fallbackUrl = null)
    {
        if ($latitude && $longitude) {
            if (!is_numeric($latitude) || !is_numeric($longitude)) {
                throw new MenuException('Latitude and longitude must be numeric');
            }

            $actionData = [
                'location' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ],
            ];
        } elseif ($query) {
            $actionData = [
                'location' => [
                    'query' => $query
                ],
            ];
        } else {
            throw new MenuException('ShowLocation must contain latitude and longitude or query');
        }

        $label && $actionData['location']['label'] = $label;
        $fallbackUrl && $actionData['fallbackUrl'] = $fallbackUrl;

        return self::action($display, [
            'mapAction' => [
                'showLocation' => $actionData
            ]
        ], $return);
    }

    /**
     * getLocation
     * @param $display
     * @param $requestLocationPush
     * @param string|null $return
     * @return Button
     */
    public static function getLocation($display, $requestLocationPush, ?string $return = null)
    {
        return self::action($display, [
            'mapAction' => [
                'requestLocationPush' => $requestLocationPush
            ]
        ], $return);
    }

    /**
     * createCalendarEvent
     * @param $display
     * @param string $startTime
     * @param string $endTime
     * @param string $title
     * @param string|null $return
     * @param string|null $description
     * @param string|null $fallbackUrl
     * @return Button
     */
    public static function createCalendarEvent($display, string $startTime, string $endTime, string $title, ?string $return = null, ?string $description = null, ?string $fallbackUrl = null)
    {
        $actionData = [
            'startTime' => $startTime,
            'endTime' => $endTime,
            'title' => $title,
        ];

        $description && $actionData['description'] = $description;
        $fallbackUrl && $actionData['fallbackUrl'] = $fallbackUrl;

        return self::action($display, [
            'calendarAction' => [
                'createCalendarEvent' => $actionData
            ]
        ], $return);
    }

    /**
     * sendText
     * @param $display
     * @param $phoneNumber
     * @param string $text
     * @param string|null $return
     * @return Button
     */
    public static function sendText($display, $phoneNumber, string $text, ?string $return = null)
    {
        $actionData = [
            'phoneNumber' => $phoneNumber,
            'text' => $text,
        ];

        return self::action($display, [
            'composeAction' => [
                'composeTextMessage' => $actionData
            ]
        ], $return);
    }

    /**
     * sendAudioOrVideo
     * @param $display
     * @param $phoneNumber
     * @param string $type
     * @param string|null $return
     * @return Button
     * @throws MenuException
     */
    public static function sendAudioOrVideo($display, $phoneNumber, string $type, ?string $return = null)
    {
        $type = strtoupper($type);

        if (in_array($type, ["AUDIO", "VIDEO"])) {
            throw new MenuException('Audio type can only be set to audio or video');
        }

        $actionData = [
            'phoneNumber' => $phoneNumber,
            'type' => $type,
        ];

        return self::action($display, [
            'composeAction' => [
                'composeRecordingMessage' => $actionData
            ]
        ], $return);
    }

    /**
     * getTerminalInfo
     * @param $display
     * @param $requestDeviceSpecifics
     * @param string|null $return
     * @return Button
     */
    public static function getTerminalInfo($display, $requestDeviceSpecifics, ?string $return = null)
    {
        return self::action($display, [
            'deviceAction' => [
                'requestDeviceSpecifics' => $requestDeviceSpecifics
            ]
        ], $return);
    }
}