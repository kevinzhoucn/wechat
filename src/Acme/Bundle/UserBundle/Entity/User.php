<?php

namespace Acme\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $devices;

    private $deviceKey;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var array
     */
    private $phones;

    /**
     * @var array
     */
    private $primaryPhone;

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

    /**
     * @var bool
     */
    private $isActive;

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
        $this->devices = new ArrayCollection();
        // $this->phones = new ArrayCollection();
        $this->phones = array();
        // $this->createdAt = time();
        $this->deviceKey = $this->getRandChar(16);
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get primaryPhone
     *
     * @return string 
     */
    public function getPrimaryPhone()
    {
        return $this->primaryPhone;
    }

    public function setPrimaryPhone($primaryPhone)
    {
        $this->primaryPhone = $primaryPhone;

        return $this;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }


    /**
     * Set roles
     *
     * @param array $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Add phone
     *
     * @param  $phone
     * @return User
     */
    // public function addPhone($phone)
    // {
    //     // echo $phone . 'exits: ' . !$this->phones->contains($phone);
    //     // if(!$this->phones->contains($phone)) {
    //     //     // $this->phones = $this->phones->add($phone);
    //     //     $this->phones->add($phone);
    //     //     // echo 'add phone:' . $phone;
    //     //     // echo implode(',', $this->phones->toArray());
    //     // }

    //     if(!in_array($phone, $this->phones)) {
    //         $this->phones[] = $phone;
    //     }
    //     return $this;
    // }

    /**
     * Set phones
     *
     * @param array $phones
     * @return User
     */
    public function setPhones($phones)
    {
        $this->phones = $phones;

        return $this;
    }

    /**
     * Get phones
     *
     * @return array 
     */
    public function getPhones()
    {
        return $this->phones;
    }

    public function getPhoneString()
    {
        return implode(',', $this->phones);
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
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
     * @return User
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
     * @return User
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
     * Add devices
     *
     * @param \Acme\Bundle\IotBundle\Entity\Device $devices
     * @return User
     */
    public function addDevice(\Acme\Bundle\IotBundle\Entity\Device $device)
    {
        if(!$this->devices->contains($device)) {
            $this->devices->add($device);
        }

        return $this;
    }

    /**
     * Remove devices
     *
     * @param \Acme\Bundle\IotBundle\Entity\Device $devices
     */
    public function removeDevice(\Acme\Bundle\IotBundle\Entity\Device $device)
    {
        $this->devices->removeElement($device);
    }

    /**
     * Get devices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDevices()
    {
        return $this->devices;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $alert_rules;


    /**
     * Add alert_rules
     *
     * @param \Acme\Bundle\AlertBundle\Entity\AlertRule $alertRules
     * @return User
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

    public function getDeviceKey()
    {
        return $this->deviceKey;
    }

    private function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for($i=0;$i<$length;$i++){
            $str .= $strPol[rand(0,$max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }
}
