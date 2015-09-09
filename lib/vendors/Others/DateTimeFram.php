<?php

namespace Others;

class DateTimeFram extends \DateTime {
    function __construct($time = "now" , $timezone = NULL ) {
        parent::__construct($time,  is_null($timezone) ?  new \DateTimeZone("UTC") : $timezone );
    }

    public function __toString(){
        return parent::format("Y-m-d");
    }
}