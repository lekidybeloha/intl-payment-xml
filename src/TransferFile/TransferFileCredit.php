<?php


namespace TransferFile;

use DomBuilder\BaseBuilder;
use Utils\StringHelpers;
use Utils\Validator as Validator;

class TransferFileCredit
{
    public static function createCustomerTransfer($identification, $initiator, $painFormat = "pain.001.001.03")
    {
        try
        {
            //Check pain format
            $result = Validator::validatePain($painFormat);
            if(!$result)
                throw new \Exception("This library support only pain.001.001.03!");

            //Sanitize initiator
            $initiator = StringHelpers::sanitizeString($initiator);

            return new BaseBuilder($painFormat);

        }catch (\Exception $ex)
        {
            printf($ex);
            die;
        }



    }
}