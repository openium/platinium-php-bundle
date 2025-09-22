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
     * List of push group
     *
     * @var string[]
     */
    protected $groups = [];

    /**
     * List of Langs
     * (in platinium : notification.lang in [...])
     *
     * @var string[]
     */
    protected $langs = [];

    /**
     * Inverse list of lang
     * (in platinium : notification.lang not in [...])
     *
     * @var boolean
     */
    protected $langNotIn = false;

    /**
     * Is geolocation config is set
     *
     * @var bool
     */
    private $isGeolocated = false;

    /**
     * Latitude for geolocation
     *
     * @var float|null
     */
    private $latitude;

    /**
     * Longitude for geolocation
     *
     * @var float|null
     */
    private $longitude;

    /**
     * Tolerance for geolocation
     *
     * @var int|null
     */
    private $tolerance;

    /**
     * Radius for geolocation
     *
     * @var int|null
     */
    private $radius;

    /**
     * PlatiniumPushInformation constructor.
     *
     * @param string[] $groups
     * @param string[] $langs
     */
    public function __construct(array $groups, array $langs, bool $langNotIn = false)
    {
        $this->groups = $groups;
        $this->langs = $langs;
        $this->langNotIn = $langNotIn;
    }

    /**
     * Getter for groups
     *
     * @return string[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * Setter for groups
     *
     * @param string[] $groups
     */
    public function setGroups(array $groups): self
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * Getter for langs
     *
     * @return string[]
     */
    public function getLangs(): array
    {
        return $this->langs;
    }

    /**
     * Setter for langs
     *
     * @param string[] $langs
     */
    public function setLangs(array $langs): self
    {
        $this->langs = $langs;
        return $this;
    }

    /**
     * Getter for latitude
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * Getter for longitude
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * Getter for tolerance
     */
    public function getTolerance(): ?int
    {
        return $this->tolerance;
    }

    /**
     * Getter for radius
     */
    public function getRadius(): ?int
    {
        return $this->radius;
    }

    /**
     * Getter for isGeolocated
     */
    public function isGeolocated(): bool
    {
        return $this->isGeolocated;
    }

    /**
     * Getter for langNotIn
     */
    public function isLangNotIn(): bool
    {
        return $this->langNotIn;
    }

    /**
     * Setter for langNotIn
     */
    public function setLangNotIn(bool $langNotIn): self
    {
        $this->langNotIn = $langNotIn;
        return $this;
    }

    /**
     * setGeolocation
     */
    public function setGeolocation(
        float $latitude,
        float $longitude,
        int $tolerance,
        int $radius
    ): self {
        if (!empty($latitude)) {
            $this->latitude = $latitude;
        }

        if (!empty($longitude)) {
            $this->longitude = $longitude;
        }

        if ($tolerance !== 0) {
            $this->tolerance = $tolerance;
        }

        if ($radius !== 0) {
            $this->radius = $radius;
        }

        $this->isGeolocated = true;
        return $this;
    }

    /**
     * isValidGeolocation
     */
    public function isValidGeolocation(): bool
    {
        if (!$this->isGeolocated) {
            return true;
        }

        return (
            $this->radius !== null
            && $this->latitude !== null
            && $this->longitude !== null
            && $this->tolerance !== null
        );
    }
}
