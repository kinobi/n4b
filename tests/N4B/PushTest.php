<?php

namespace N4B;


class PushTest extends \PHPUnit_Framework_TestCase
{
    private $beAppName = 'testApp';
    private $beAppId = 13;
    private $beAppVersion = 1;
    private $beAppSecret = 'Sup3rS3cr3tch41n';

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

    /**
     * @return Push
     */
    private function instantiatePush()
    {
        $n4bPush = new Push($this->beAppName, $this->beAppId, $this->beAppVersion, $this->beAppSecret);

        return $n4bPush;
    }
}
