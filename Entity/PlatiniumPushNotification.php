<?php
/**
 * PHP Version 7.1, 7.2
 *
 * @package  Openium\PlatiniumBundle
 * @author   Openium <contact@openium.fr>
 * @license  Openium All right reserved
 * @link     https://www.openium.fr/
 */

namespace Openium\PlatiniumBundle\Entity;

/**
 * Class PlatiniumPushNotification
 *
 * @package Openium\PlatiniumBundle\Entity
 */
class PlatiniumPushNotification
{
    /**
     * Message of the push notification
     *
     * @var null|string
     */
    protected $message;

    /**
     * Value of the application badge
     *
     * @var int
     */
    protected $badgeValue = 0;

    /**
     * Name of the sound integrated in your application
     *
     * @var null|string
     */
    protected $sound;

    /**
     * Is notification newsstand
     * for silent push
     *
     * @var bool
     */
    protected $newsStand = false;

    /**
     * Array of additionnal parameters
     *
     * @var array<string, string>
     */
    protected $paramsBag = [];

    /**
     * PlatiniumPushNotification constructor.
     *
     * @param string|null $message
     * @param array<string, string> $paramsBag
     * @param string|null $sound
     */
    public function __construct(
        string $message = null,
        array $paramsBag = [],
        int $badgeValue = 0,
        bool $newsStand = false,
        string $sound = null
    ) {
        $this->message = $message;
        $this->paramsBag = $paramsBag;
        $this->badgeValue = $badgeValue;
        $this->newsStand = $newsStand;
        $this->sound = $sound;
    }

    /**
     * jsonFormat
     */
    public function jsonFormat(): string
    {
        $jsonArray = ['newsstand' => $this->isNewsStand() ? 1 : 0];
        if (!empty($this->message)) {
            $jsonArray['message'] = $this->message;
        }

        if (!empty($this->sound)) {
            $jsonArray['sound'] = $this->sound;
        }

        if (!empty($this->badgeValue)) {
            $jsonArray['badge'] = $this->badgeValue;
        }

        if (!empty($this->paramsBag)) {
            $jsonArray['paramsbag'] = $this->paramsBag;
        }

        return sprintf('[%s]', json_encode($jsonArray));
    }

    /**
     * addAdditionalParameter
     */
    public function addAdditionalParameter(string $key, string $value): self
    {
        $this->paramsBag[$key] = $value;
        return $this;
    }

    /**
     * Getter for message
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Setter for message
     */
    public function setMessage(?string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Getter for badgeValue
     */
    public function getBadgeValue(): int
    {
        return $this->badgeValue;
    }

    /**
     * Setter for badgeValue
     */
    public function setBadgeValue(int $badgeValue): self
    {
        $this->badgeValue = $badgeValue;
        return $this;
    }

    /**
     * Getter for sound
     */
    public function getSound(): ?string
    {
        return $this->sound;
    }

    /**
     * Setter for sound
     */
    public function setSound(?string $sound): self
    {
        $this->sound = $sound;
        return $this;
    }

    /**
     * Getter for newsStand
     */
    public function isNewsStand(): bool
    {
        return $this->newsStand;
    }

    /**
     * Setter for newsStand
     */
    public function setNewsStand(bool $newsStand): self
    {
        $this->newsStand = $newsStand;
        return $this;
    }

    /**
     * Getter for paramsBag
     *
     * @return array<string, string>
     */
    public function getParamsBag(): array
    {
        return $this->paramsBag;
    }

    /**
     * Setter for paramsBag
     *
     * @param array<string, string> $paramsBag
     */
    public function setParamsBag(array $paramsBag): self
    {
        $this->paramsBag = $paramsBag;
        return $this;
    }
}
