<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:03 上午
 */

namespace Easy5G\Chatbot\TemplateMessage;


use Easy5G\Chatbot\Application;
use Easy5G\Kernel\Exceptions\InvalidArgumentException;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Kernel\Support\Xml;
use Illuminate\Contracts\Container\BindingResolutionException;

class ChinaMobile extends Client
{
    protected $batchSendUrl = '%s/vg2/messaging/messaging/group/template/outbound/%s/requests';
    protected $batchReplyUrl = '%s/vg2/messaging/messaging/interaction/template/outbound/{chatbotURI}/requests';
    protected $batchSendVariableUrl = '%s/vg2/messaging/sendTask/sendVariable';
    protected $serviceProvider = Const5G::CM;

    /**
     * batchSend
     * @param array $data
     * @return string
     * @throws BindingResolutionException|InvalidConfigException|InvalidISPException
     */
    public function batchSend(array $data)
    {
        $this->checkBatchData($data, 'send');

        /** @var Application $app */
        $app = $this->app;

        return $app->httpClient->post($this->getCurrentUrl('batchSend'), [
            'headers' => [
                'Authorization' => $app->access_token->getToken(),
                'userId' => $app->config->get($this->serviceProvider)['userId'],
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ],
            'body' => Xml::build($data, 'xml', ['version' => '1.0', 'encoding' => 'UTF-8'], 'msg:outboundMessageRequest', 'xmlns:msg="urn:oma:xml:rest:netapi:messaging:1"')
        ]);
    }

    /**
     * batchReply
     * @param array $data
     * @return string
     * @throws BindingResolutionException|InvalidConfigException|InvalidISPException
     */
    public function batchReply(array $data)
    {
        $this->checkBatchData($data, 'reply');

        /** @var Application $app */
        $app = $this->app;

        return $app->httpClient->post($this->getCurrentUrl('batchReply'), [
            'headers' => [
                'Authorization' => $app->access_token->getToken(),
                'userId' => $app->config->get($this->serviceProvider)['userId'],
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ],
            'body' => Xml::build($data, 'xml', ['version' => '1.0', 'encoding' => 'UTF-8'], 'msg:outboundMessageRequest', 'xmlns:msg="urn:oma:xml:rest:netapi:messaging:1"')
        ]);
    }

    /**
     * batchSendVariable
     * @param array $data
     * @return string
     * @throws BindingResolutionException|InvalidConfigException|InvalidISPException
     */
    public function batchSendVariable(array $data)
    {
        $this->checkVariableData($data);

        /** @var Application $app */
        $app = $this->app;

        return $app->httpClient->post($this->getCurrentUrl('batchSendVariable'), [
            'headers' => [
                'Authorization' => $app->access_token->getToken(),
                'Content-Type' => 'application/json',
                'userId' => $app->config->get($this->serviceProvider)['userId'],
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ],
            'json' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        ]);
    }

    /**
     * checkBatchData
     * @param array $data
     * @param $action
     */
    protected function checkBatchData(array &$data, $action)
    {
        if ($action === 'reply' && empty($data['inReplyToContributionID'])) {
            throw new InvalidArgumentException('InReplyToContributionID must be filled in');
        }

        if (empty($data['destinationAddress'])) {
            throw new InvalidArgumentException('Destination address must be filled in');
        }

        if (is_array($data['destinationAddress'])) {
            foreach ($data['destinationAddress'] as &$mobile) {
                $mobile = $this->parseMobile($mobile);
            }
        } else {
            $data['destinationAddress'] = [$this->parseMobile($data['destinationAddress'])];
        }

        $data['contentType'] = 'static-template';

        $data['contentEncoding'] = 'utf8';

        if (empty($data['bodyText'])) {
            throw new InvalidArgumentException('Body text must be filled in');
        }

        if (empty(json_decode($data['bodyText'], true))) {
            $data['bodyText'] = '{"templateID":"' . $data['bodyText'] . '"}';
        }

        $data['bodyText'] = Xml::cdata($data['bodyText']);

        if (empty($data['contributionID'])) {
            throw new InvalidArgumentException('Contribution ID must be filled in');
        }

        if (isset($data['fallbackSupported']) && $data['fallbackSupported'] === true) {
            $data['fallbackContentType'] = 'static-template';

            if (empty(json_decode($data['rcsBodyText'], true))) {
                $data['rcsBodyText'] = '{"templateID":"' . $data['rcsBodyText'] . '"}';
            }

            $data['rcsBodyText'] = Xml::cdata($data['rcsBodyText']);
        }
    }

    /**
     * checkVariableData
     * @param array $data
     * @throws InvalidArgumentException
     */
    protected function checkVariableData(array &$data)
    {
        if (empty($data['id'])) {
            throw new InvalidArgumentException('Id must be filled in');
        }

        if (empty($data['transactionId'])) {
            throw new InvalidArgumentException('TransactionId must be filled in');
        }

        if (empty($data['datas'])) {
            throw new InvalidArgumentException('Datas must be filled in');
        }

        if (empty($data['variables'])) {
            throw new InvalidArgumentException('Datas.variables must be filled in');
        }

        if (empty($data['values'])) {
            throw new InvalidArgumentException('Datas.values must be filled in');
        }

        if (is_array($data['datas']['variables'])) {
            $data['datas']['variables'] = implode('|',$data['datas']['variables']);
        }

        foreach ($data['datas']['values'] as &$value) {
            if (empty($value['mobile']) || empty($value['value'])) {
                throw new InvalidArgumentException('Datas.values error');
            }

            if (is_array($value['value'])) {
                $value['value'] = implode('|',$value['value']);
            }
        }
    }

    /**
     * parseMobile
     * @param $mobile
     * @return string
     * @throws InvalidArgumentException
     */
    protected function parseMobile($mobile)
    {
        $prefix = 'tel:+';

        if (is_int($mobile)) {
            return $prefix . $mobile;
        }

        if (is_string($mobile)) {
            if (strpos($mobile, $prefix) === false) {
                $mobile = $prefix . $mobile;
            }

            return $mobile;
        }

        throw new InvalidArgumentException('Destination address:' . $mobile . ' error');
    }
}