<?php

namespace N4B;

class PushTest extends \PHPUnit_Framework_TestCase
{
    private $beAppName = 'testPush';
    private $beAppId = 13;
    private $beAppVersion = 2;
    private $beAppSecret = 'Sup3rS3cr3tch41n4Push';

    public function testItCanBeInstantiate()
    {
        $n4bPush = $this->instantiatePush();

        $this->assertInstanceOf(Push::class, $n4bPush);
        $this->assertEquals($this->beAppName, $n4bPush->getBeappName());
        $this->assertEquals($this->beAppId, $n4bPush->getBeappId());
        $this->assertEquals($this->beAppVersion, $n4bPush->getBeappVersion());
        $this->assertEquals($this->beAppSecret, $n4bPush->getBeappSecret());
    }

    public function testItCheckTheUrgencyLevel()
    {
        $this->expectException(\OutOfRangeException::class);
        $n4bPush = $this->instantiatePush();
        $n4bPush->send('someNotification', 'userId1', [], rand(100, 200));
    }

    public function testItCanSendPushMessage()
    {
        $n4bPush = $this->instantiatePush();
        $url = sprintf('https://web.be-bound.com/n4bp/%s_%s/%s', $this->beAppName, $this->beAppId, $this->beAppVersion);
        file_put_contents($url, json_encode(['userId1' => 1]));

        $response = $n4bPush->send('someNotification', 'userId1', [], Push::N4B_PUSH_URGENCY_BEBOUND_ONLY);

        $this->assertArrayHasKey('userId1', $response);
    }

    protected function setUp()
    {
        stream_wrapper_unregister("https");
        stream_wrapper_register("https", "N4B\\Mocks\\MockPhpStream");
    }

    protected function tearDown()
    {
        stream_wrapper_restore("https");
    }

    /**
     * @return Push
     */
    private function instantiatePush()
    {
        $n4bPush = new Push($this->beAppName, $this->beAppId, $this->beAppVersion, $this->beAppSecret);

        return $n4bPush;
    }
}
