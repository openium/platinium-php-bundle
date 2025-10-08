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
    protected array $languages = [];

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
     * in days
     * allow to omit users who have not updated their position for more than X days
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
        $this->languages = $langs;
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
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * Setter for langs
     *
     * @param string[] $languages
     */
    public function setLanguages(array $languages): self
    {
        $this->languages = $languages;
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
        ?int $tolerance,
        int $radius
    ): self {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->tolerance = $tolerance;
        $this->radius = $radius;
        $isValidTolerance = $tolerance === null || ($tolerance && $tolerance > 0);
        $isValid = $isValidTolerance
            && $radius > 0
            && !($latitude === 0.0 && $longitude === 0.0);
        $this->isGeolocated = $isValid;
        return $this;
    }
}
