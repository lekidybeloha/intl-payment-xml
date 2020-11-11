<?php


namespace Utils;


class Validator
{
    public static function validatePain($painFormat)
    {
        if($painFormat != 'pain.001.001.03')
                return false;
        else
            return true;
    }
}