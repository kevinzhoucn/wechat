<?php

namespace Acme\Bundle\FedexBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acquire
 */
class Acquire
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $eventType;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var string
     */
    private $announceDate;

    /**
     * @var string
     */
    private $expEffDate;

    /**
     * @var string
     */
    private $unconditionalDate;

    /**
     * @var string
     */
    private $acqtech;

    /**
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $companyName;

    /**
     * @var string
     */
    private $flag0;

    /**
     * @var string
     */
    private $country0;

    /**
     * @var string
     */
    private $country1;

    /**
     * @var string
     */
    private $flag1;

    /**
     * @var string
     */
    private $number1;

    /**
     * @var string
     */
    private $number2;

    /**
     * @var string
     */
    private $number3;

    /**
     * @var string
     */
    private $ric0;

    /**
     * @var string
     */
    private $ric1;

    /**
     * @var string
     */
    private $sedol;

    /**
     * @var string
     */
    private $code0;

    /**
     * @var string
     */
    private $code1;

    /**
     * @var string
     */
    private $flagType;

    /**
     * @var string
     */
    private $companyDescription;


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
     * Set eventType
     *
     * @param string $eventType
     * @return Acquire
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;

        return $this;
    }

    /**
     * Get eventType
     *
     * @return string 
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * Set transactionId
     *
     * @param string $transactionId
     * @return Acquire
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set announceDate
     *
     * @param string $announceDate
     * @return Acquire
     */
    public function setAnnounceDate($announceDate)
    {
        $this->announceDate = $announceDate;

        return $this;
    }

    /**
     * Get announceDate
     *
     * @return string 
     */
    public function getAnnounceDate()
    {
        return $this->announceDate;
    }

    /**
     * Set expEffDate
     *
     * @param string $expEffDate
     * @return Acquire
     */
    public function setExpEffDate($expEffDate)
    {
        $this->expEffDate = $expEffDate;

        return $this;
    }

    /**
     * Get expEffDate
     *
     * @return string 
     */
    public function getExpEffDate()
    {
        return $this->expEffDate;
    }

    /**
     * Set unconditionalDate
     *
     * @param string $unconditionalDate
     * @return Acquire
     */
    public function setUnconditionalDate($unconditionalDate)
    {
        $this->unconditionalDate = $unconditionalDate;

        return $this;
    }

    /**
     * Get unconditionalDate
     *
     * @return string 
     */
    public function getUnconditionalDate()
    {
        return $this->unconditionalDate;
    }

    /**
     * Set acqtech
     *
     * @param string $acqtech
     * @return Acquire
     */
    public function setAcqtech($acqtech)
    {
        $this->acqtech = $acqtech;

        return $this;
    }

    /**
     * Get acqtech
     *
     * @return string 
     */
    public function getAcqtech()
    {
        return $this->acqtech;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Acquire
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     * @return Acquire
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set flag0
     *
     * @param string $flag0
     * @return Acquire
     */
    public function setFlag0($flag0)
    {
        $this->flag0 = $flag0;

        return $this;
    }

    /**
     * Get flag0
     *
     * @return string 
     */
    public function getFlag0()
    {
        return $this->flag0;
    }

    /**
     * Set country0
     *
     * @param string $country0
     * @return Acquire
     */
    public function setCountry0($country0)
    {
        $this->country0 = $country0;

        return $this;
    }

    /**
     * Get country0
     *
     * @return string 
     */
    public function getCountry0()
    {
        return $this->country0;
    }

    /**
     * Set country1
     *
     * @param string $country1
     * @return Acquire
     */
    public function setCountry1($country1)
    {
        $this->country1 = $country1;

        return $this;
    }

    /**
     * Get country1
     *
     * @return string 
     */
    public function getCountry1()
    {
        return $this->country1;
    }

    /**
     * Set flag1
     *
     * @param string $flag1
     * @return Acquire
     */
    public function setFlag1($flag1)
    {
        $this->flag1 = $flag1;

        return $this;
    }

    /**
     * Get flag1
     *
     * @return string 
     */
    public function getFlag1()
    {
        return $this->flag1;
    }

    /**
     * Set number1
     *
     * @param string $number1
     * @return Acquire
     */
    public function setNumber1($number1)
    {
        $this->number1 = $number1;

        return $this;
    }

    /**
     * Get number1
     *
     * @return string 
     */
    public function getNumber1()
    {
        return $this->number1;
    }

    /**
     * Set number2
     *
     * @param string $number2
     * @return Acquire
     */
    public function setNumber2($number2)
    {
        $this->number2 = $number2;

        return $this;
    }

    /**
     * Get number2
     *
     * @return string 
     */
    public function getNumber2()
    {
        return $this->number2;
    }

    /**
     * Set number3
     *
     * @param string $number3
     * @return Acquire
     */
    public function setNumber3($number3)
    {
        $this->number3 = $number3;

        return $this;
    }

    /**
     * Get number3
     *
     * @return string 
     */
    public function getNumber3()
    {
        return $this->number3;
    }

    /**
     * Set ric0
     *
     * @param string $ric0
     * @return Acquire
     */
    public function setRic0($ric0)
    {
        $this->ric0 = $ric0;

        return $this;
    }

    /**
     * Get ric0
     *
     * @return string 
     */
    public function getRic0()
    {
        return $this->ric0;
    }

    /**
     * Set ric1
     *
     * @param string $ric1
     * @return Acquire
     */
    public function setRic1($ric1)
    {
        $this->ric1 = $ric1;

        return $this;
    }

    /**
     * Get ric1
     *
     * @return string 
     */
    public function getRic1()
    {
        return $this->ric1;
    }

    /**
     * Set sedol
     *
     * @param string $sedol
     * @return Acquire
     */
    public function setSedol($sedol)
    {
        $this->sedol = $sedol;

        return $this;
    }

    /**
     * Get sedol
     *
     * @return string 
     */
    public function getSedol()
    {
        return $this->sedol;
    }

    /**
     * Set code0
     *
     * @param string $code0
     * @return Acquire
     */
    public function setCode0($code0)
    {
        $this->code0 = $code0;

        return $this;
    }

    /**
     * Get code0
     *
     * @return string 
     */
    public function getCode0()
    {
        return $this->code0;
    }

    /**
     * Set code1
     *
     * @param string $code1
     * @return Acquire
     */
    public function setCode1($code1)
    {
        $this->code1 = $code1;

        return $this;
    }

    /**
     * Get code1
     *
     * @return string 
     */
    public function getCode1()
    {
        return $this->code1;
    }

    /**
     * Set flagType
     *
     * @param string $flagType
     * @return Acquire
     */
    public function setFlagType($flagType)
    {
        $this->flagType = $flagType;

        return $this;
    }

    /**
     * Get flagType
     *
     * @return string 
     */
    public function getFlagType()
    {
        return $this->flagType;
    }

    /**
     * Set companyDescription
     *
     * @param string $companyDescription
     * @return Acquire
     */
    public function setCompanyDescription($companyDescription)
    {
        $this->companyDescription = $companyDescription;

        return $this;
    }

    /**
     * Get companyDescription
     *
     * @return string 
     */
    public function getCompanyDescription()
    {
        return $this->companyDescription;
    }
}
