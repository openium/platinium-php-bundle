<?php

namespace Openium\PlatiniumBundle\Platinium;

class PPSMockedNotification extends PPSNotification
{
    public function getJSON()
    {
        $json = parent::getJSON();
        $array = json_decode($json, true);
        $array['nodeMocked'] = true;
        $json = json_encode($array);

        return $json;
    }
}
