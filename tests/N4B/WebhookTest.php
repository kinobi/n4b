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

    /**
     * @return Webhook
     */
    private function instantiateWebhook()
    {
        $n4b = new Webhook($this->beAppName, $this->beAppId, $this->beAppVersion, $this->beAppSecret);

        return $n4b;
    }
}
