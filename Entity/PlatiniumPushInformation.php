<?php

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
    protected array $groups = [];

    /**
     * List of Langs
     * (in platinium : notification.lang in [...])
     *
     * @var string[]
     */
    protected array $langs = [];

    /**
     * Inverse list of lang
     * (in platinium : notification.lang not in [...])
     */
    protected bool $langNotIn = false;

    /**
     * Is geolocation config is set
     */
    private bool $isGeolocated = false;

    /**
     * Latitude for geolocation
     */
    private ?float $latitude = null;

    /**
     * Longitude for geolocation
     */
    private ?float $longitude = null;

    /**
     * Tolerance for geolocation
     */
    private ?int $tolerance = null;

    /**
     * Radius for geolocation
     */
    private ?int $radius = null;

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

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function getTolerance(): ?int
    {
        return $this->tolerance;
    }

    public function getRadius(): ?int
    {
        return $this->radius;
    }

    public function isGeolocated(): bool
    {
        return $this->isGeolocated;
    }

    public function isLangNotIn(): bool
    {
        return $this->langNotIn;
    }

    public function setLangNotIn(bool $langNotIn): self
    {
        $this->langNotIn = $langNotIn;
        return $this;
    }

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
