<?php
/**
 * Copyright (c) 19/11/2020 16:27 DIMBINIAINA Elkana Vinet
 * XML international transaction
 */

namespace Utils;


class Validator
{
    /**
     * @param $painFormat
     * @return bool
     */
    public static function validatePain($painFormat)
    {
    	return $painFormat == 'pain.001.001.03';
    }
}