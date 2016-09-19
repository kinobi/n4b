<?php

namespace N4B;

abstract class HandlerAbstract
{
    protected $beappName;
    protected $beappId;
    protected $beappVersion;
    protected $beappSecret;

    public function __construct($beappName, $beappId, $beappVersion, $beappSecret)
    {
        $this->setBeappName($beappName);
        $this->setBeappId($beappId);
        $this->setBeappVersion($beappVersion);
        $this->setBeappSecret($beappSecret);
    }

    /**
     * Get the BeApp name.
     *
     * @return string
     */
    public function getBeappName()
    {
        return $this->beappName;
    }

    /**
     * Set the BeApp name.
     *
     * @param string $beappName
     */
    public function setBeappName($beappName)
    {
        $this->beappName = $beappName;
    }

    /**
     * Get the BeApp Id.
     *
     * @return int
     */
    public function getBeappId()
    {
        return $this->beappId;
    }

    /**
     * Set the BeApp Id number.
     *
     * @param mixed $beappId
     */
    public function setBeappId($beappId)
    {
        $this->beappId = intval($beappId);
    }

    /**
     * Get the BeApp version number.
     *
     * @return int
     */
    public function getBeappVersion()
    {
        return $this->beappVersion;
    }

    /**
     * Set the BeApp version number.
     *
     * @param int $beappVersion
     */
    public function setBeappVersion($beappVersion)
    {
        $this->beappVersion = intval($beappVersion);
    }

    /**
     * Get the module auth secret.
     *
     * @return string
     */
    public function getBeappSecret()
    {
        return $this->beappSecret;
    }

    /**
     * Set the module auth secret.
     *
     * @param string $beappSecret
     */
    public function setBeappSecret($beappSecret)
    {
        $this->beappSecret = $beappSecret;
    }
}
