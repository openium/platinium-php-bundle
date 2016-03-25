<?php

namespace Openium\PlatiniumBundle\Platinium;

class PPSNotification
{
    // Message of the push notification
    private $message = null;
    // Value of the application badge
    private $badgeValue = 0;
    // Name of the sound integrated in your application
    private $sound = null;
    // Is notification newsstand
    private $newsstand = false;
    // Array of additionnal parameters
    private $paramsbag = null;

    /**
     * PPSNotification constructor.
     * @param string $message Message of the push notification
     * @param int $badgeValue Value of the application badge
     * @param bool $newsstand Is notification newsstand
     * @param string $sound Name of the sound integrated in your application
     */
    function __construct($message = null, $badgeValue = 0, $newsstand = false, $sound = null)
    {
        $this->badgeValue = $badgeValue;
        $this->message = $message;
        $this->badgeValue = $badgeValue;
        $this->sound = $sound;
    }

    public function getJSON()
    {
        $jsonArray = array();

        if ($this->newsstand) {
            $jsonArray['newsstand'] = 1;
        }
        if ($this->message) {
            $jsonArray['message'] = $this->message;
        }
        if ($this->sound) {
            $jsonArray['sound'] = $this->sound;
        }
        if ($this->badgeValue) {
            $jsonArray['badge'] = $this->badgeValue;
        }
        if ($this->paramsbag) {
            $jsonArray['paramsbag'] = $this->paramsbag;
        }

        return json_encode($jsonArray);
    }

    public function addAdditionalParameter($key, $value)
    {
        if ($this->paramsbag == null) {
            $this->paramsbag = array();
        }
        $this->paramsbag[(string)$key] = (string)$value;
    }

    /**
     * @return string The message of the application
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return int The value of the badge
     */
    public function getBadgeValue()
    {
        return $this->badgeValue;
    }

    /**
     * @param int $badgeValue
     */
    public function setBadgeValue($badgeValue)
    {
        $this->badgeValue = $badgeValue;
    }

    /**
     * @return string The notification sound
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * @param string $sound
     */
    public function setSound($sound)
    {
        $this->sound = $sound;
    }

    /**
     * @return boolean Is the notification newsstand
     */
    public function isNewsstand()
    {
        return $this->newsstand;
    }

    /**
     * @param boolean $newsstand
     */
    public function setNewsstand($newsstand)
    {
        $this->newsstand = $newsstand;
    }
}

?>
