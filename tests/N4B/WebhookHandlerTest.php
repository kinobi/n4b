<?php

namespace N4B;


class WebhookHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $beAppName = 'testApp';
    private $beAppId = 13;
    private $beAppVersion = 1;
    private $beAppSecret = 'Sup3rS3cr3tch41n';

    public function testItCanBeInstantiate()
    {
        $n4b = new WebhookHandler($this->beAppName, $this->beAppId, $this->beAppVersion, $this->beAppSecret);
        $this->assertInstanceOf(WebhookHandler::class, $n4b);
    }
}
