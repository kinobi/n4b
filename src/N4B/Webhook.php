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
                return; // This request doesn't concern this handler
            }

            $this->logger->info(sprintf('Received operation %s', $data['operation']), ['params' => $data['params'], 'transport' => $data['transport'], 'userId' => $data['userId']]);

            if ((bool) $options['authCheck']) {
                $this->checkAuth();
            }

            if (!array_key_exists($data['operation'], $this->operationsMap)) {
                $this->logger->error('No callable mapped to this operation', ['operationName' => $data['operation']]);
                throw new Error('BB_ERROR_METHOD_NOT_FOUND');
            }

            $out = $this->operationsMap[$data['operation']]((array) $data['params'], $data['transport'],
                $data['userId']);
            return $this->sendResponse(['params' => $out], $options['getResponse']);
        } catch (Error $e) {
            $n4bError = ['error' => $e->getMessage()];
            $this->logger->notice('Returning Be-App Error', $n4bError);
            return $this->sendResponse($n4bError, $options['getResponse']);
        } catch (Exception $e) {
            if (!(bool) $options['catchAll']) {
                throw $e;
            }
            $this->logger->critical('Uncaught exception in the operation handler',
                ['exception', $e, 'operation' => $data['operation']]);
            return $this->sendResponse(['error' => 'BB_ERROR_UNKNOWN_USER_SPECIFIED_ERROR'], $options['getResponse']);
        }
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

    private function checkAuth()
    {
        $auth_usr = isset($_SERVER ['PHP_AUTH_USER']) ? $_SERVER ['PHP_AUTH_USER'] : null;
        $auth_pwd = isset($_SERVER ['PHP_AUTH_PW']) ? $_SERVER ['PHP_AUTH_PW'] : null;
        if ($auth_usr != sprintf('%s_%s', $this->beappName,
                $this->beappId) || $auth_pwd != $this->beappSecret
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

    private function sendResponse($out, $getResponse = false)
    {
        $response = json_encode($out);

        if ($getResponse) {
            $this->logger->info('Returns string response.');
            return $response;
        }

        $this->logger->info('Returns json HTTP response.');
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate'); // No Cache: HTTP/1.1
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // No Cache: in the past
        header('Content-Length: ' . strlen($response));
        echo $response;
        return true;
    }
}
