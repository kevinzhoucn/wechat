<?php

namespace Acme\Bundle\AlertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AlertRule
 */
class AlertRule
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $alertRule;

    /**
     * @var string
     */
    private $informRule;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set alertRule
     *
     * @param string $alertRule
     * @return AlertRule
     */
    public function setAlertRule($alertRule)
    {
        $this->alertRule = $alertRule;

        return $this;
    }

    /**
     * Get alertRule
     *
     * @return string 
     */
    public function getAlertRule()
    {
        return $this->alertRule;
    }

    /**
     * Set informRule
     *
     * @param string $informRule
     * @return AlertRule
     */
    public function setInformRule($informRule)
    {
        $this->informRule = $informRule;

        return $this;
    }

    /**
     * Get informRule
     *
     * @return string 
     */
    public function getInformRule()
    {
        return $this->informRule;
    }

    /**
     * @var \Acme\Bundle\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Acme\Bundle\IotBundle\Entity\Device
     */
    private $device;


    /**
     * Set user
     *
     * @param \Acme\Bundle\UserBundle\Entity\User $user
     * @return AlertRule
     */
    public function setUser(\Acme\Bundle\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Acme\Bundle\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set device
     *
     * @param \Acme\Bundle\IotBundle\Entity\Device $device
     * @return AlertRule
     */
    public function setDevice(\Acme\Bundle\IotBundle\Entity\Device $device = null)
    {
        $this->device = $device;

        return $this;
    }

    /**
     * Get device
     *
     * @return \Acme\Bundle\IotBundle\Entity\Device 
     */
    public function getDevice()
    {
        return $this->device;
    }
}
