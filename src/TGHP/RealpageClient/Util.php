<?php

namespace TGHP\RealpageClient;

class Util
{

    public static $dateFormat = 'm/d/Y';

    /**
     * Override default date format
     *
     * @param $format
     */
    public static function setDateFormat($format)
    {
        self::$dateFormat = $format;
    }

    /**
     * Format criterion values that get passed to realpage
     *
     * @param $value
     * @return string
     */
    public static function formatCriterionValue($value)
    {
        if($value instanceof \DateTime) {
            $value = $value->format(self::$dateFormat);
        } else if (is_bool($value)) {
            $value = $value ? 'True' : 'False';
        }

        return $value;
    }

}