<?php

namespace Acme\Bundle\IotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DataPoint
 */
class DataPoint
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $data;
    
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
        // $this->datapoints = new ArrayCollection();
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
     * Set data
     *
     * @param string $data
     * @return DataPoint
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return string 
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * @var \Acme\Bundle\IotBundle\Entity\Device
     */
    private $device;


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return DataPoint
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
     * @return DataPoint
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
     * @return DataPoint
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
     * Set device
     *
     * @param \Acme\Bundle\IotBundle\Entity\Device $device
     * @return DataPoint
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