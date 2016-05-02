<?php

namespace Acme\Bundle\IotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Device
 */
class Device
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $sn;

    /**
     * @var string
     */
    private $model;

    /**
     * @var string
     */
    private $vender;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $datapoints;

    /**
     * @var string
     */
    private $nextAlertTime;    

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    public function __construct()
    {
        $this->datapoints = new ArrayCollection();
        // $this->createdAt = time();
    }

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
     * Set name
     *
     * @param string $name
     * @return Device
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Device
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Device
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Device
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }
    /**
     * @var \Acme\Bundle\UserBundle\Entity\User
     */
    private $user;


    /**
     * Set user
     *
     * @param \Acme\Bundle\UserBundle\Entity\User $user
     * @return Device
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
     * Set sn
     *
     * @param string $sn
     * @return Device
     */
    public function setSn($sn)
    {
        $this->sn = $sn;

        return $this;
    }

    /**
     * Get sn
     *
     * @return string 
     */
    public function getSn()
    {
        return $this->sn;
    }

    /**
     * Set model
     *
     * @param string $model
     * @return Device
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string 
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set vender
     *
     * @param string $vender
     * @return Device
     */
    public function setVender($vender)
    {
        $this->vender = $vender;

        return $this;
    }

    /**
     * Get vender
     *
     * @return string 
     */
    public function getVender()
    {
        return $this->vender;
    }

    /**
     * Set next alert time
     *
     * @param string $nextAlertTime
     * @return Device
     */
    public function setNextAlertTime($nextAlertTime)
    {
        $this->nextAlertTime = $nextAlertTime;

        return $this;
    }

    /**
     * Get nextAlertTime
     *
     * @return string 
     */
    public function getNextAlertTime()
    {
        return $this->nextAlertTime;
    }

    /**
     * Add datapoints
     *
     * @param \Acme\Bundle\IotBundle\Entity\DataPoint $datapoints
     * @return Device
     */
    public function addDatapoint(\Acme\Bundle\IotBundle\Entity\DataPoint $datapoint)
    {
        $this->datapoints[] = $datapoint;

        return $this;
    }

    /**
     * Remove datapoints
     *
     * @param \Acme\Bundle\IotBundle\Entity\DataPoint $datapoints
     */
    public function removeDatapoint(\Acme\Bundle\IotBundle\Entity\DataPoint $datapoint)
    {
        $this->datapoints->removeElement($datapoint);
    }

    /**
     * Get datapoints
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDatapoints()
    {
        return $this->datapoints;
    }

    public function getAlertMobiles()
    {
        $mobiles = "";

        if($this->user) {
            // echo count($this->user->getPhones()->toArray());
            $mobiles = implode(',', $this->user->getPhones()->toArray());
        }

        return $mobiles;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $alert_rules;


    /**
     * Add alert_rules
     *
     * @param \Acme\Bundle\AlertBundle\Entity\AlertRule $alertRules
     * @return Device
     */
    public function addAlertRule(\Acme\Bundle\AlertBundle\Entity\AlertRule $alertRules)
    {
        $this->alert_rules[] = $alertRules;

        return $this;
    }

    /**
     * Remove alert_rules
     *
     * @param \Acme\Bundle\AlertBundle\Entity\AlertRule $alertRules
     */
    public function removeAlertRule(\Acme\Bundle\AlertBundle\Entity\AlertRule $alertRules)
    {
        $this->alert_rules->removeElement($alertRules);
    }

    /**
     * Get alert_rules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlertRules()
    {
        return $this->alert_rules;
    }
}
