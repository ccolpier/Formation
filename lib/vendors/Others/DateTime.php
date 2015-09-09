<?php
/**
 * Projet :  framework_madnetix.
 * User: mvedie
 * Date: 28/01/2015
 * Time: 09:51
 */

namespace DreamCentury\Core\Lib;

class DateTime extends \DateTime {


    private static $_Formatter_a;

    function __construct($time = "now" , $timezone = NULL ) {
        parent::__construct($time,  is_null($timezone) ?  new \DateTimeZone("UTC") : $timezone );
    }

    public function formatToSQL() {
        return substr($this->format("Y-m-d G:i:s.u"),0,-3);
    }

    public function formatUsingTimeZone($format) {
        $Date = clone $this;
        $Date->setTimezone(new \DateTimeZone(Application::getInstance()->Session()->TIMEZONE_CODE));
        return $Date->format($format);
    }

    public function __toString() {
        return $this->toString();
    }

    public function toString($date_format = 'short',$time_format = 'medium') {
        // Date & Time Format:
        // NONE, NONE  : 20150522 09:39 AM
        // Time Format :
        // SHORT : 09:39
        // MEDIUM : 09:39:59
        // LONG : 09:39:59 UTC-07:00
        // FULL : 09:39:59 heure avancée du Pacifique
        // Date Format :
        // SHORT : 22/05/15
        // MEDIUM : 22 mai 2015
        // LONG : 22 mai 2015
        // FULL : vendredi 22 mai 2015
        return static::getFormatter($date_format,$time_format)->format($this);
    }


    static public function createFromFormat($format, $time)
    {

        $ext_dt = new static();
        $ext_dt->setTimestamp(parent::createFromFormat($format, $time)->getTimestamp());
        return $ext_dt;
    }


    /**
     * @param $date_format
     * @param $time_format
     * @return \IntlDateFormatter
     */
    private static function getFormatter($date_format,$time_format) {
        if (isset(static::$_Formatter_a[$date_format.'_'.$time_format])) return static::$_Formatter_a[$date_format.'_'.$time_format];

        $locale = Application::getInstance()->Session()->LOCALE;
        $timezone = Application::getInstance()->Session()->TIMEZONE_CODE;

        $DATE_FORMAT = strtoupper($date_format);
        $TIME_FORMAT = strtoupper($time_format);

        static::$_Formatter_a[$date_format.'_'.$time_format] = new \IntlDateFormatter( $locale ,constant('\IntlDateFormatter::'.$DATE_FORMAT), constant('\IntlDateFormatter::'.$TIME_FORMAT), $timezone, \IntlDateFormatter::GREGORIAN );
        return static::$_Formatter_a[$date_format.'_'.$time_format];
    }



    public function isAfter(DateTime $date) {
        $timestamp_this = $this->format('U').'.'.$this->format('u');
        $timestamp_date = $date->format('U').'.'.$this->format('u');
        return $timestamp_this > $timestamp_date;
    }

    public function isBefore(DateTime $date) {
        $timestamp_this = $this->format('U').'.'.$this->format('u');
        $timestamp_date = $date->format('U').'.'.$this->format('u');
        return $timestamp_this < $timestamp_date;
    }

}
?>