<?php

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
     */
    protected ?string $message = null;

    /**
     * Value of the application badge
     */
    protected int $badgeValue = 0;

    /**
     * Name of the sound integrated in your application
     */
    protected ?string $sound = null;

    /**
     * Is notification newsstand
     * for silent push
     */
    protected bool $newsStand = false;

    /**
     * Array of additionnal parameters
     *
     * @var array<string, string>
     */
    protected array $paramsBag = [];

    /**
     * PlatiniumPushNotification constructor.
     *
     * @param array<string, string> $paramsBag
     */
    public function __construct(
        ?string $message = null,
        array $paramsBag = [],
        int $badgeValue = 0,
        bool $newsStand = false,
        ?string $sound = null
    ) {
        $this->message = $message;
        $this->paramsBag = $paramsBag;
        $this->badgeValue = $badgeValue;
        $this->newsStand = $newsStand;
        $this->sound = $sound;
    }

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

    public function addAdditionalParameter(string $key, string $value): self
    {
        $this->paramsBag[$key] = $value;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getBadgeValue(): int
    {
        return $this->badgeValue;
    }

    public function setBadgeValue(int $badgeValue): self
    {
        $this->badgeValue = $badgeValue;
        return $this;
    }

    public function getSound(): ?string
    {
        return $this->sound;
    }

    public function setSound(?string $sound): self
    {
        $this->sound = $sound;
        return $this;
    }

    public function isNewsStand(): bool
    {
        return $this->newsStand;
    }

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
