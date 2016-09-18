<?php

namespace N4B;


abstract class HandlerAbstract
{
    protected $moduleName;
    protected $moduleId;
    protected $moduleVersion;
    protected $modulePassword;

    public function __construct($moduleName, $moduleId, $moduleVersion, $modulePassword)
    {
        $this->setModuleName($moduleName);
        $this->setModuleId($moduleId);
        $this->setModuleVersion($moduleVersion);
        $this->setModulePassword($modulePassword);
    }

    /**
     * Get the BeApp name
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * Set the BeApp name
     * @param string $moduleName
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * Get the BeApp Id
     * @return integer
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * Set the BeApp Id number
     * @param mixed $moduleId
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = intval($moduleId);
    }

    /**
     * Get the BeApp version number
     * @return integer
     */
    public function getModuleVersion()
    {
        return $this->moduleVersion;
    }

    /**
     * Set the BeApp version number
     * @param integer $moduleVersion
     */
    public function setModuleVersion($moduleVersion)
    {
        $this->moduleVersion = intval($moduleVersion);
    }

    /**
     * Get the module auth secret
     * @return string
     */
    public function getModulePassword()
    {
        return $this->modulePassword;
    }

    /**
     * Set the module auth secret
     * @param string $modulePassword
     */
    public function setModulePassword($modulePassword)
    {
        $this->modulePassword = $modulePassword;
    }
}
