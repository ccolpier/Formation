<?php

namespace Others;

class DateTimeFram extends \DateTime {
    public function __toString(){
        return parent::format("Y-m-d");
    }
}