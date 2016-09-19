<?php

namespace N4B;

class Push extends HandlerAbstract
{
    const N4B_URL_PATTERN = 'https://web.be-bound.com/n4bp/%s_%s/%s';
    const N4B_PUSH_URGENCY_BEBOUND_ONLY = 1;
    const N4B_PUSH_URGENCY_HIGH = 2;
    const N4B_PUSH_URGENCY_DATA_ONLY = 5;

    protected static $n4bAvailableUrgencies = [
        self::N4B_PUSH_URGENCY_BEBOUND_ONLY,
        self::N4B_PUSH_URGENCY_HIGH,
        self::N4B_PUSH_URGENCY_DATA_ONLY,
    ];

    /**
     * Send a push message through N4B.
     *
     * @param $operation
     * @param string|array $users
     * @param array        $params
     * @param int          $urgency
     *
     * @return array
     */
    public function send($operation, $users, array $params = [], $urgency = self::N4B_PUSH_URGENCY_BEBOUND_ONLY)
    {
        if (!is_array($users)) {
            $users = (array) $users;
        }

        if (!in_array($urgency, static::$n4bAvailableUrgencies)) {
            throw new \OutOfRangeException('Push urgency not available');
        }

        $context = $this->createContext($operation, $users, $params, $urgency);
        $url = $this->createUrl();
        $response = file_get_contents($url, false, $context);

        return json_decode($response, true);
    }

    private function createContext($operation, array $users, array $params, $urgency)
    {
        $data = json_encode(
            [
                'operation' => $operation,
                'userId'    => $users,
                'params'    => $params,
                'urgency'   => $urgency,
            ]
        );

        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => [
                    'Content-type: application/json',
                    'Authorization: Basic '.$this->generateBearer(),
                ],
                'content' => $data,
            ],
        ];

        return stream_context_create($opts);
    }

    private function generateBearer()
    {
        $bearer = sprintf('%s_%s:%s', $this->beappName, $this->beappId, $this->beappSecret);

        return base64_encode($bearer);
    }

    private function createUrl()
    {
        return sprintf(static::N4B_URL_PATTERN, $this->beappName, $this->beappId, $this->beappVersion);
    }
}
