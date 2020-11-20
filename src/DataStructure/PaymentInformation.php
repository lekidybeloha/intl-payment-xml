<?php
/**
 * Copyright (c) 19/11/2020 16:26 DIMBINIAINA Elkana Vinet
 * XML international transaction
 */

namespace DataStructure;


use DomBuilder\BaseBuilder;
use Utils\StringHelpers;

class PaymentInformation
{
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
     * @var int
     */
    protected $transactionNumber    = 0;
    /**
     * @var
     */
    protected $transactionRef;
    /**
     * @var int
     */
    protected $controlPrice         = 0;
    /**
     * @var array
     */
    protected $payments             = [];
    /**
     * @var
     */
    protected $currentPayment;

    /**
     * PaymentInformation constructor.
     * @param $MSGID
     * @param $initiator
     */
    public function __construct($MSGID, $initiator)
    {
        $this->MSGID        = $MSGID;
        $this->initiator    = $initiator;
    }

    /**
     * @param $ref
     * @param $debtorName
     * @param $debtorIBAN
     * @param $debtorBIC
     */
    public function addPaymentInfo($ref, $debtorName, $debtorIBAN, $debtorBIC)
    {
        $this->ref          = $ref;
        $this->debtorName   = $debtorName;
        $this->debtorIBAN   = $debtorIBAN;
        $this->debtorBIC    = $debtorBIC;
        $this->dateTime     = new \DateTime();
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
    public function createTransaction(float $amount, $creditorIBAN, $creditorBIC, $creditorName, $reason, $ref)
    {
        $this->payments[] = [
                                        'amount'    =>  $amount,
                                        'iban'      =>  $creditorIBAN,
                                        'bic'       =>  $creditorBIC,
                                        'name'      =>  $creditorName,
                                        'reason'    =>  $reason,
                                        'ref'       =>  $ref
                                ];
        $this->transactionNumber++;
        $this->controlPrice += $amount;
    }

    /**
     * @return false|string
     */
    public function build()
    {
        $builder                = new BaseBuilder("pain.001.001.03");
        $entete                 = $builder->doc->createElement('CstmrCdtTrfInitn');
        $this->traitment($builder->doc, $entete);
        $builder->root->appendChild($entete);

        return $builder->asXml();
    }

    /**
     * This function create all the transaction inside the XML file
     * @param $document
     * @param $root
     */
    protected function traitment($document, $root)
    {
        //Build header
        $header = new Header();
        $header->build($document, $root, $this->MSGID, $this->transactionNumber, $this->debtorName, $this->controlPrice);
        //Build transfertInformation
        $this->paymentInformationTraitment($document, $header);
        //Treat each transaction
        foreach ($this->payments as $payment)
        {
            $transaction =  new Transaction();
            $transaction->insertTransaction($document, $payment, $this->currentPayment);
        }

        $root->appendChild($this->currentPayment);
    }

    /**
     * This function generate the Debitor information
     * @param $document
     * @param $header
     */
    protected function paymentInformationTraitment($document, $header)
    {
        $this->currentPayment   = $document->createElement('PmtInf');
        $this->currentPayment->appendChild($document->createElement('PmtInfId', $this->ref));
        $this->currentPayment->appendChild($document->createElement('PmtMtd', "TRF"));
        $this->currentPayment->appendChild(
            $document->createElement('NbOfTxs', $this->transactionNumber)
        );
        $this->currentPayment->appendChild(
            $document->createElement('CtrlSum', $this->controlPrice)
        );

        $paymentTypeInformation = $document->createElement('PmtTpInf');
        $instructionPriority    = $document->createElement('InstrPrty', "NORM");
        $paymentTypeInformation->appendChild($instructionPriority);
        $this->currentPayment->appendChild($paymentTypeInformation);

        $this->currentPayment->appendChild($document->createElement('ReqdExctnDt', $header->getFormattedDate()));
        $debtor                 = $document->createElement('Dbtr');
        $debtor->appendChild($document->createElement('Nm', StringHelpers::sanitizeString($this->debtorName)));
        $this->currentPayment->appendChild($debtor);

        $debtorAccount          = $document->createElement('DbtrAcct');
        $id = $document->createElement('Id');
        $id->appendChild($document->createElement('IBAN', $this->debtorIBAN));
        $debtorAccount->appendChild($id);
        $this->currentPayment->appendChild($debtorAccount);
        $debtorAgent            = $document->createElement('DbtrAgt');
        $finInstitution         = $document->createElement('FinInstnId');
        $finInstitution->appendChild($document->createElement('BIC', $this->debtorBIC));

        $financialInstitutionId = $finInstitution;
        $debtorAgent->appendChild($financialInstitutionId);
        $this->currentPayment->appendChild($debtorAgent);

        $this->currentPayment->appendChild($document->createElement('ChrgBr', 'DEBT'));

    }
}