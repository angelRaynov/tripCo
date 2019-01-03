<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * OfferType
 *
 * @ORM\Table(name="offers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OfferRepository")
 */
class Offer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="start_destination", type="string", length=255)
     */
    private $startDestination;

    /**
     * @var string
     *
     * @ORM\Column(name="end_destination", type="string", length=255)
     */
    private $endDestination;

    /**
     * @var datetime
     *
     * @ORM\Column(name="date", type="datetime", length=255)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="hour", type="string", length=255)
     */
    private $hour;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="seats", type="integer")
     */
    private $seats;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="luggage", type="string", length=255)
     */
    private $luggage;

    /**
     * @var Car
     *
     * @ORM\Column(name="car", type="string", length=255)
     */
    private $car;

    /**
     * @var int
     *
     * @ORM\Column(name="driver_id", type="integer")
     */
    private $driverId;


    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="offers")
     * @ORM\JoinColumn(name="driver_id", referencedColumnName="id")
     */
    private $driver;

    /**
     * @return \AppBundle\Entity\User
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param \AppBundle\Entity\User $driver
     *
     * @return Offer
     */
    public function setDriver(User $driver = null)
    {
        $this->driver = $driver;

        return $this;
    }
    /**
     * @return int
     */
    public function getDriverId()
    {
        return $this->driverId;
    }

    /**
     * @param int $driverId
     *
     * @return Offer
     */
    public function setDriverId($driverId)
    {
        $this->driverId = $driverId;

        return $this;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startDestination
     *
     * @param string $startDestination
     *
     * @return Offer
     */
    public function setStartDestination($startDestination)
    {
        $this->startDestination = $startDestination;

        return $this;
    }

    /**
     * Get startDestination
     *
     * @return string
     */
    public function getStartDestination()
    {
        return $this->startDestination;
    }

    /**
     * Set endDestination
     *
     * @param string $endDestination
     *
     * @return Offer
     */
    public function setEndDestination($endDestination)
    {
        $this->endDestination = $endDestination;

        return $this;
    }

    /**
     * Get endDestination
     *
     * @return string
     */
    public function getEndDestination()
    {
        return $this->endDestination;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return Offer
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set hour
     *
     * @param string $hour
     *
     * @return Offer
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * Get hour
     *
     * @return string
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Offer
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set seats
     *
     * @param integer $seats
     *
     * @return Offer
     */
    public function setSeats($seats)
    {
        $this->seats = $seats;

        return $this;
    }

    /**
     * Get seats
     *
     * @return int
     */
    public function getSeats()
    {
        return $this->seats;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Offer
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set luggage
     *
     * @param string $luggage
     *
     * @return Offer
     */
    public function setLuggage($luggage)
    {
        $this->luggage = $luggage;

        return $this;
    }

    /**
     * Get luggage
     *
     * @return string
     */
    public function getLuggage()
    {
        return $this->luggage;
    }

    /**
     * Set car
     *
     * @param string $car
     *
     * @return Offer
     */
    public function setCar($car)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * Get car
     *
     * @return string
     */
    public function getCar()
    {
        return $this->car;
    }
}

