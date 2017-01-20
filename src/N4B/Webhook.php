<?php

namespace N4B;

use Exception;

class Webhook extends HandlerAbstract
{
    protected $operationsMap = [];

    /**
     * Handle a N4B request.
     *
     * @param array $options
     *
     * @return bool|string
     * @throws Exception
     *
     * @internal param bool $authCheck
     * @internal param bool $debug
     */
    public function run(array $options = [])
    {
        $options = array_merge([
            'authCheck' => true,
            'catchAll' => true,
            'getResponse' => false,
        ], $options);

        try {
            $data = $this->parseRequestBody();

            if ($this->beappName != $data['moduleName']
                || $this->beappId != $data['moduleId']
                || $this->beappVersion != $data['moduleVersion']
            ) {
                $this->logger->notice('The request is not relevant for this webhook.');
                return null; // This request doesn't concern this handler
            }

            $this->logger->info(sprintf('Received operation %s', $data['operation']),
                ['params' => $data['params'], 'transport' => $data['transport'], 'userId' => $data['userId']]);

            $this->checkAuth((bool) $options['authCheck']);

            if (!array_key_exists($data['operation'], $this->operationsMap)) {
                $this->logger->error('No callable mapped to this operation', ['operationName' => $data['operation']]);
                throw new Error('BB_ERROR_METHOD_NOT_FOUND');
            }

            $out = $this->operationsMap[$data['operation']]((array) $data['params'], $data['transport'],
                $data['userId']);
            $n4bResponse = ['params' => $out];
        } catch (Error $e) {
            $n4bResponse = ['error' => $e->getMessage()];
            $this->logger->notice('Returning Be-App Error', $n4bResponse);
        } catch (Exception $e) {
            if (!(bool) $options['catchAll']) {
                throw $e;
            }

            $this->logger->critical('Uncaught exception in the operation handler',
                ['exception', $e, 'operation' => $data['operation']]);
            $n4bResponse = ['error' => 'BB_ERROR_UNKNOWN_USER_SPECIFIED_ERROR'];
        }

        return (bool) $options['getResponse'] ? $this->getResponse($n4bResponse) : $this->sendResponse($n4bResponse);
    }

    /**
     * Map a callable to an operation describes in the BeApp Manifest.
     *
     * @param          $operation
     * @param callable $handler
     */
    public function add($operation, callable $handler)
    {
        $this->logger->debug('Operation handler added', ['operation' => $operation]);
        $this->operationsMap[$operation] = $handler;
    }

    private function checkAuth($checkFlag)
    {
        if (!$checkFlag) {
            return true;
        }

        $authUser = isset($_SERVER ['PHP_AUTH_USER']) ? filter_var($_SERVER ['PHP_AUTH_USER']) : null;
        $authPassword = isset($_SERVER ['PHP_AUTH_PW']) ? filter_var($_SERVER ['PHP_AUTH_PW']) : null;
        if ($authUser != sprintf('%s_%s', $this->beappName,
                $this->beappId) || $authPassword != $this->beappSecret
        ) {
            $this->logger->error('Authentication of the request failed.');
            throw new Error('BB_ERROR_AUTHORIZATION');
        }

        return true;
    }

    private function parseRequestBody()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $this->logger->error('Empty request body.');
            throw new Error('BB_ERROR_REQUEST_REJECTED');
        }

        return $data;
    }

    private function sendResponse($out)
    {
        $response = json_encode($out);

        $this->logger->info('Returns json HTTP response.');
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate'); // No Cache: HTTP/1.1
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // No Cache: in the past
        header('Content-Length: ' . strlen($response));
        echo $response;
        return true;
    }

    private function getResponse($out)
    {
        $this->logger->info('Returns string response.');
        return json_encode($out);
    }
}
