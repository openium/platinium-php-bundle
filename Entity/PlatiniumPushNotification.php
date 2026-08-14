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
     * PlatiniumPushNotification constructor.
     *
     * @param array<string, string> $paramsBag
     */
    public function __construct(
        /**
         * Message of the push notification
         */
        protected ?string $message = null,
        /**
         * Array of additionnal parameters
         */
        protected array $paramsBag = [],
        /**
         * Value of the application badge
         */
        protected int $badgeValue = 0,
        /**
         * Is notification newsstand
         * for silent push
         */
        protected bool $newsStand = false,
        /**
         * Name of the sound integrated in your application
         */
        protected ?string $sound = null
    )
    {
    }

    public function jsonFormat(): string
    {
        $jsonArray = ['newsstand' => $this->isNewsStand() ? 1 : 0];
        if (!in_array($this->message, [null, '', '0'], true)) {
            $jsonArray['message'] = $this->message;
        }

        if (!in_array($this->sound, [null, '', '0'], true)) {
            $jsonArray['sound'] = $this->sound;
        }

        if ($this->badgeValue !== 0) {
            $jsonArray['badge'] = $this->badgeValue;
        }

        if ($this->paramsBag !== []) {
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
