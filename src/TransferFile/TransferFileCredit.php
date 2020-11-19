<?php
/**
 * Copyright (c) 19/11/2020 16:26 DIMBINIAINA Elkana Vinet
 * XML international transaction
 */

namespace TransferFile;

use DataStructure\PaymentInformation;
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
            return new PaymentInformation($identification, $initiator);

        }catch (\Exception $ex)
        {
            printf($ex);
            die;
        }
    }

}