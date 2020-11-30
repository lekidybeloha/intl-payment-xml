<?php
	/**
	 * Copyright (c) 19/11/2020 16:26 DIMBINIAINA Elkana Vinet
	 * XML international transaction
	 */

	namespace DataStructure;

	use DomBuilder\BaseBuilder;
	use Library\Exception\InvalidArgumentException;
	use Utils\StringHelpers;
	use Utils\TimeZone;

	class PaymentInformation
	{
		/**
		 * @var string
		 */
		protected $painFormat = "pain.001.001.03";
		/**
		 * @var
		 */
		protected $MSGID;
		/**
		 * @var
		 */
		protected $initiator;
		/**
		 * @var
		 */
		protected $ref;
		/**
		 * @var
		 */
		protected $debtorName;
		/**
		 * @var
		 */
		protected $debtorIBAN;
		/**
		 * @var
		 */
		protected $debtorBIC;
		/**
		 * @var
		 */
		protected $dateTime;
		/**
		 * @var
		 */
		protected $timezone;
		/**
		 * @var int
		 */
		protected $transactionNumber = 0;
		/**
		 * @var
		 */
		protected $transactionRef;
		/**
		 * @var int
		 */
		protected $controlPrice = 0;
		/**
		 * @var array
		 */
		protected $payments = [];
		/**
		 * @var
		 */
		protected $currentPayment;

		/**
		 * PaymentInformation constructor.
		 * @param $MSGID
		 * @param $initiator
		 */
		public function __construct ( $MSGID, $initiator, $timezone )
		{
			$this->MSGID = $MSGID;
			$this->initiator = $initiator;
			$this->timezone = $timezone;
		}

		/**
		 * @param $ref
		 * @param $debtorName
		 * @param $debtorIBAN
		 * @param $debtorBIC
		 */
		public function addPaymentInfo ( $ref, $debtorInfo = [] )
		{
			if ( empty( $debtorInfo ) )
			{
				throw new InvalidArgumentException( 'debtorInfo info required' );
			}

			$required = [ 'debtorName', 'debtorIBAN', 'debtorBIC' ];

			foreach ( $required as $field )
			{
				if ( !isset( $debtorInfo[ $field ] ) || !$debtorInfo[ $field ] )
				{
					throw new InvalidArgumentException( "$field is required and can not be empty" );
				}
			}

			$debtorName = $debtorInfo[ 'debtorName' ];
			$debtorIBAN = $debtorInfo[ 'debtorIBAN' ];
			$debtorBIC = $debtorInfo[ 'debtorBIC' ];

			TimeZone::setTimeZone( $this->timezone );
			$this->ref = $ref;
			$this->debtorName = $debtorName;
			$this->debtorIBAN = $debtorIBAN;
			$this->debtorBIC = $debtorBIC;
			$this->dateTime = new \DateTime( $this->timezone );
		}

		/**
		 * This function is used to create a payment Transaction
		 * @param float $amount
		 * @param $creditorIBAN
		 * @param $creditorBIC
		 * @param $creditorName
		 * @param $reason
		 * @param $ref
		 */
		public function createTransaction ( $ref, $payment = [] )
		{

			if ( empty( $payment ) )
			{
				throw new InvalidArgumentException( 'Payment info required' );
			}

			$required = [ 'amount', 'creditorBIC', 'creditorName', 'reason' ];

			foreach ( $required as $field )
			{
				if ( !isset( $payment[ $field ] ) || !$payment[ $field ] )
				{
					throw new InvalidArgumentException( "$field is required and can not be empty" );
				}
			}

			$amount = (float) $payment[ 'amount' ];
			$creditorBIC = $payment[ 'creditorBIC' ];
			$creditorName =  $payment[ 'creditorName' ];
			$reason = $payment[ 'reason' ];
			$creditorIBAN = '';
			$creditorAccountNumber = '';

			if (isset($payment['creditorIBAN']) && $payment['creditorIBAN'] != '')
			{
				$creditorIBAN = $payment[ 'creditorIBAN' ];
			}
			elseif (isset($payment['creditorAccountNumber']) && $payment['creditorAccountNumber'] != '')
			{
				$creditorAccountNumber = $payment[ 'creditorAccountNumber' ];
			}
			else
			{
				throw new InvalidArgumentException(
					"You must provid at least one of this creditorIBAN or creditorAccountNumber "
				);
			}

			$this->payments[] = [
				'amount' => $amount,
				'iban' => $creditorIBAN,
				'accountNumber' => $creditorAccountNumber,
				'bic' => $creditorBIC,
				'name' => $creditorName,
				'reason' => $reason,
				'ref' => $ref
			];
			$this->transactionNumber++;
			$this->controlPrice += $amount;
		}

		/**
		 * @return false|string
		 */
		public function build ($is_xml_header=false)
		{
			$builder = new BaseBuilder( $this->painFormat );
			$entete = $builder->doc->createElement( 'CstmrCdtTrfInitn' );
			$this->traitment( $builder->doc, $entete );
			$builder->root->appendChild( $entete );

			return $builder->asXml($is_xml_header);
		}

		/**
		 * This function create all the transaction inside the XML file
		 * @param $document
		 * @param $root
		 */
		protected function traitment ( $document, $root )
		{
			//Build header
			$header = new Header();
			$header->build( $document, $root, $this->MSGID, $this->transactionNumber, $this->debtorName, $this->controlPrice );
			//Build transfertInformation
			$this->paymentInformationTraitment( $document, $header );
			//Treat each transaction
			foreach ( $this->payments as $payment )
			{
				$transaction = new Transaction();
				$transaction->insertTransaction( $document, $payment, $this->currentPayment );
			}

			$root->appendChild( $this->currentPayment );
		}

		/**
		 * This function generate the Debitor information
		 * @param $document
		 * @param $header
		 */
		protected function paymentInformationTraitment ( $document, $header )
		{
			$this->currentPayment = $document->createElement( 'PmtInf' );
			$this->currentPayment->appendChild( $document->createElement( 'PmtInfId', $this->ref ) );
			$this->currentPayment->appendChild( $document->createElement( 'PmtMtd', "TRF" ) );
			$this->currentPayment->appendChild(
				$document->createElement( 'NbOfTxs', $this->transactionNumber )
			);
			$this->currentPayment->appendChild(
				$document->createElement( 'CtrlSum', $this->controlPrice )
			);

			$paymentTypeInformation = $document->createElement( 'PmtTpInf' );
			$instructionPriority = $document->createElement( 'InstrPrty', "NORM" );
			$paymentTypeInformation->appendChild( $instructionPriority );
			$this->currentPayment->appendChild( $paymentTypeInformation );

			$this->currentPayment->appendChild( $document->createElement( 'ReqdExctnDt', $header->getFormattedDate() ) );
			$debtor = $document->createElement( 'Dbtr' );
			$debtor->appendChild( $document->createElement( 'Nm', StringHelpers::sanitizeString( $this->debtorName ) ) );
			$this->currentPayment->appendChild( $debtor );

			$debtorAccount = $document->createElement( 'DbtrAcct' );
			$id = $document->createElement( 'Id' );
			$id->appendChild( $document->createElement( 'IBAN', $this->debtorIBAN ) );
			$debtorAccount->appendChild( $id );
			$this->currentPayment->appendChild( $debtorAccount );
			$debtorAgent = $document->createElement( 'DbtrAgt' );
			$finInstitution = $document->createElement( 'FinInstnId' );
			$finInstitution->appendChild( $document->createElement( 'BIC', $this->debtorBIC ) );

			$financialInstitutionId = $finInstitution;
			$debtorAgent->appendChild( $financialInstitutionId );
			$this->currentPayment->appendChild( $debtorAgent );

			$this->currentPayment->appendChild( $document->createElement( 'ChrgBr', 'DEBT' ) );

		}
	}