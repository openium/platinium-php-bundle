<?php

/**
 * PHP Version 7.1, 7.2
 *
 * @package  Openium\PlatiniumBundle\Entity
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace Openium\PlatiniumBundle\Entity;

/**
 * Class PlatiniumPushInformation
 *
 * @package Openium\PlatiniumBundle\Entity
 */
class PlatiniumPushInformation
{
    /**
     * @var array
     */
    protected $groups;

    /**
     * @var array
     */
    protected $langs;

    /**
     * @var bool
     */
    private $isGeolocated = false;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var int
     */
    private $tolerance;

    /**
     * @var int
     */
    private $radius;

    /**
     * PlatiniumPushInformation constructor.
     *
     * @param array $groups
     * @param array $langs
     */
    public function __construct(array $groups, array $langs)
    {
        $this->groups = $groups;
        $this->langs = $langs;
    }

    /**
     * Getter for groups
     *
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * Setter for groups
     *
     * @param array $groups
     *
     * @return self
     */
    public function setGroups(array $groups): self
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * Getter for langs
     *
     * @return array
     */
    public function getLangs(): array
    {
        return $this->langs;
    }

    /**
     * Setter for langs
     *
     * @param array $langs
     *
     * @return self
     */
    public function setLangs(array $langs): self
    {
        $this->langs = $langs;
        return $this;
    }

    /**
     * Getter for latitude
     *
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * Getter for longitude
     *
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * Getter for tolerance
     *
     * @return int|null
     */
    public function getTolerance(): ?int
    {
        return $this->tolerance;
    }

    /**
     * Getter for radius
     *
     * @return int|null
     */
    public function getRadius(): ?int
    {
        return $this->radius;
    }

    /**
     * Getter for isGeolocated
     *
     * @return bool
     */
    public function isGeolocated(): bool
    {
        return $this->isGeolocated;
    }

    /**
     * setGeolocation
     *
     * @param float $latitude
     * @param float $longitude
     * @param int $tolerance
     * @param int $radius
     *
     * @return PlatiniumPushInformation
     */
    public function setGeolocation(float $latitude, float $longitude, int $tolerance, int $radius): self
    {
        if (!empty($latitude)) {
            $this->latitude = $latitude;
        }
        if (!empty($longitude)) {
            $this->longitude = $longitude;
        }
        if (!empty($tolerance)) {
            $this->tolerance = $tolerance;
        }
        if (!empty($radius)) {
            $this->radius = $radius;
        }
        $this->isGeolocated = true;
        return $this;
    }

    /**
     * isValidGeolocation
     *
     * @return bool
     */
    public function isValidGeolocation(): bool
    {
        if (!$this->isGeolocated) {
            return true;
        } else {
            return (
                !empty($this->radius)
                && !empty($this->latitude)
                && !empty($this->longitude)
                && !empty($this->tolerance)
            );
        }
    }
}
