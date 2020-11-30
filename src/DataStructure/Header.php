<?php
	/**
	 * Copyright (c) 19/11/2020 16:25 DIMBINIAINA Elkana Vinet
	 * XML international transaction
	 */

	namespace DataStructure;

	use Utils\StringHelpers;

	class Header
	{
		/**
		 * @var \DateTime
		 */
		protected $creationDate;

		protected $timezone = '';

		/**
		 * Header constructor.
		 */
		public function __construct ($europe='Europe/Paris')
		{
			$this->timezone = $europe;
			$this->creationDate = new \DateTime($this->timezone);
		}

		/**
		 * This will construct the XML header
		 * @param $document
		 * @param $root
		 * @param $MSGID
		 * @param $transactionNumber
		 * @param $debtorname
		 * @param $price
		 */
		public function build ( $document, $root, $MSGID, $transactionNumber, $debtorname, $price )
		{
			$groupHeaderTag = $document->createElement( 'GrpHdr' );
			$messageId = $document->createElement( 'MsgId', $MSGID );
			$groupHeaderTag->appendChild( $messageId );
			$creationDateTime = $document->createElement(
				'CreDtTm',
				$this->creationDate->format( 'Y-m-d\TH:i:s\Z' )
			);
			$groupHeaderTag->appendChild( $creationDateTime );
			$groupHeaderTag->appendChild( $document->createElement( 'NbOfTxs', $transactionNumber ) );
			$groupHeaderTag->appendChild(
				$document->createElement( 'CtrlSum', $price )
			);

			$initiatingParty = $document->createElement( 'InitgPty' );
			$initiatingPartyName = $document->createElement( 'Nm', StringHelpers::sanitizeString( $debtorname ) );
			$initiatingParty->appendChild( $initiatingPartyName );
			$groupHeaderTag->appendChild( $initiatingParty );
			$root->appendChild( $groupHeaderTag );
			$root->appendChild( $groupHeaderTag );
		}

		public function getFormattedDate ()
		{
			return $this->creationDate->format( 'Y-m-d' );
		}
	}