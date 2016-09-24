<?php

namespace N4B;

class WebhookTest extends \PHPUnit_Framework_TestCase
{
    private $beAppName = 'testApp';
    private $beAppId = 13;
    private $beAppVersion = 1;
    private $beAppSecret = 'Sup3rS3cr3tch41n';

    public function testItCanBeInstantiate()
    {
        $n4b = $this->instantiateWebhook();

        $this->assertInstanceOf(Webhook::class, $n4b);
        $this->assertEquals($this->beAppName, $n4b->getBeappName());
        $this->assertEquals($this->beAppId, $n4b->getBeappId());
        $this->assertEquals($this->beAppVersion, $n4b->getBeappVersion());
        $this->assertEquals($this->beAppSecret, $n4b->getBeappSecret());
    }

    public function testItCanBeUpdated()
    {
        $n4b = $this->instantiateWebhook();
        $newBeappName = 'testAppUpdated';
        $newBeappId = 42;
        $newBeappVersion = 2;
        $newBeappSecret = '4n0th3r$3cr3T';

        $n4b->setBeappName($newBeappName);
        $this->assertEquals($newBeappName, $n4b->getBeappName());

        $n4b->setBeappId($newBeappId);
        $this->assertEquals($newBeappId, $n4b->getBeappId());

        $n4b->setBeappVersion($newBeappVersion);
        $this->assertEquals($newBeappVersion, $n4b->getBeappVersion());

        $n4b->setBeappSecret($newBeappSecret);
        $this->assertEquals($newBeappSecret, $n4b->getBeappSecret());
    }

    public function testItCanReturnErrorBB_ERROR_METHOD_NOT_FOUND()
    {
        $n4b = $this->instantiateWebhook();
        $this->setRequestData();

        $response = $n4b->run(['getResponse' => true]);
        $this->assertJsonStringEqualsJsonString('{"error":"BB_ERROR_METHOD_NOT_FOUND"}', $response);
    }

    public function testItCanCatchForgottenExceptions()
    {
        $operationName = 'opWithException';
        $n4b = $this->instantiateWebhook();
        $n4b->add($operationName, function () {
            throw new \RuntimeException('Forget to catch this one');
        });

        $this->setRequestData($operationName);
        $response = $n4b->run(['getResponse' => true]);
        $this->assertJsonStringEqualsJsonString('{"error":"BB_ERROR_UNKNOWN_USER_SPECIFIED_ERROR"}', $response);
    }

    public function testCatchAllOptionCanBeTurnedOff()
    {
        $this->expectException(\RuntimeException::class);
        $operationName = 'opWithException';
        $n4b = $this->instantiateWebhook();
        $n4b->add($operationName, function () {
            throw new \RuntimeException('Forget to catch this one');
        });

        $this->setRequestData($operationName);
        $n4b->run(['catchAll' => false]);
    }

    public function testItcanCheckWrongBasicAuthorisation()
    {
        $n4b = $this->instantiateWebhook();
        $n4b->setBeappSecret('changedSecret');
        $response = $n4b->run(['getResponse' => true]);
        $this->assertJsonStringEqualsJsonString('{"error":"BB_ERROR_AUTHORIZATION"}', $response);
    }

    /**
     * @runInSeparateProcess
     */
    public function testItCanSendResponse()
    {
        $this->expectOutputString('{"params":{"yodaShout":"YOU MUST UNLEARN WHAT YOU HAVE LEARNED."}}');
        $operation = 'shout';
        $n4b = $this->instantiateWebhook();
        $this->setRequestData($operation, ['yodaTalk' => 'you must unlearn what you have learned.']);

        $n4b->add($operation, function ($params) {
            return ['yodaShout' => strtoupper($params['yodaTalk'])];
        });

        $n4b->run(['getResponse' => false]);
    }

    public function testItCanRejectEmptyRequest()
    {
        $n4b = $this->instantiateWebhook();
        file_put_contents('php://input', 'notAJsonString');
        $response = $n4b->run(['getResponse' => true]);
        $this->assertJsonStringEqualsJsonString('{"error":"BB_ERROR_REQUEST_REJECTED"}', $response);
    }

    public function testItCanIgnoreIrrelevantRequest()
    {
        $n4b = $this->instantiateWebhook();
        $this->setRequestData('anyOperation', ['luke' => 'jedi'], 504, 'empireStrikesBack', 5);
        $return = $n4b->run();
        $this->assertNull($return);
    }

    protected function setUp()
    {
        stream_wrapper_unregister("php");
        stream_wrapper_register("php", "N4B\\Mocks\\MockPhpStream");
    }

    protected function tearDown()
    {
        stream_wrapper_restore("php");
    }

    /**
     * @return Webhook
     */
    private function instantiateWebhook()
    {
        $n4b = new Webhook($this->beAppName, $this->beAppId, $this->beAppVersion, $this->beAppSecret);

        return $n4b;
    }

    private function setRequestData(
        $operationName = 'myOperation',
        array $params = [],
        $moduleId = null,
        $moduleName = null,
        $moduleVersion = null
    ) {
        $moduleId = $moduleId ?: $this->beAppId;
        $moduleName = $moduleName ?: $this->beAppName;
        $moduleVersion = $moduleVersion ?: $this->beAppVersion;
        $data = sprintf('{"transport":"web","userId":"id","moduleId":%s,"moduleName":"%s","moduleVersion":%s,"operation":"%s","params":%s}',
            $moduleId, $moduleName, $moduleVersion, $operationName, json_encode($params));
        file_put_contents('php://input', $data);
    }
}
