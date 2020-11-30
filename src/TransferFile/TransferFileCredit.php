<?php
	/**
	 * Copyright (c) 19/11/2020 16:26 DIMBINIAINA Elkana Vinet
	 * XML international transaction
	 */

	namespace TransferFile;

	use DataStructure\PaymentInformation;
	use Library\Exception\InvalidArgumentException;
	use Utils\Validator as Validator;

	class TransferFileCredit
	{
		/**
		 * This function initialize all Class for gebin the XML file generation
		 * For this time, its support only pain.001.001.03
		 * @param $identification
		 * @param $initiator
		 * @param string $painFormat
		 * @return PaymentInformation
		 */
		public static function createCustomerTransfer ( $identification, $initiator, $painFormat = "pain.001.001.03", $timezone = "UTC" )
		{
			try
			{
				//Check pain format
				$result = Validator::validatePain( $painFormat );
				if ( !$result )
				{
					throw new InvalidArgumentException( "This library support only pain.001.001.03 !" );
				}
				return new PaymentInformation( $identification, $initiator, $timezone );

			}
			catch ( \Exception $ex )
			{
				die( $ex->getMessage() );
			}
		}

	}