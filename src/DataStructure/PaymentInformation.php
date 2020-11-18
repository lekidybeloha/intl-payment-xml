<?php


namespace DataStructure;


use DomBuilder\BaseBuilder;

class PaymentInformation
{
    protected $ref;
    protected $debtorName;
    protected $debtorIBAN;
    protected $debtorBIC;
    protected $dateTime;
    protected $transactionNumber    = 0;
    protected $controlPrice         = 0;
    protected $payments             = [];
    protected $currentPayment;

    public function addPaymentInfo($ref, $debtorName, $debtorIBAN, $debtorBIC)
    {
        $this->ref          = $ref;
        $this->debtorName   = $debtorName;
        $this->debtorIBAN   = $debtorIBAN;
        $this->debtorBIC    = $debtorBIC;
        $this->dateTime     = new \DateTime();
        //Header::build($this->ref, $this->debtorName, $this->debtorIBAN, $this->debtorBIC);
    }

    public function createTransaction(float $amount, $creditorIBAN, $creditorBIC, $creditorName, $reason)
    {
        $this->payments[$reason] = [
                                        'amount'    =>  $amount,
                                        'iban'      =>  $creditorIBAN,
                                        'bic'       =>  $creditorBIC,
                                        'name'      =>  $creditorName,
                                        'reason'    =>  $reason,
                                ];
        $this->transactionNumber++;
        $this->controlPrice += $amount;
    }

    public function build()
    {
        $builder                = new BaseBuilder("pain.001.001.03");
        $entete                 = $builder->doc->createElement('CstmrCdtTrfInitn');
        $builder->root->appendChild($entete);

        //Build header
        $groupHeaderTag         = $builder->doc->createElement('GrpHdr');
        $messageId              = $builder->doc->createElement('MsgId', $this->ref);
        $groupHeaderTag->appendChild($messageId);
        $creationDateTime       = $builder->doc->createElement(
                                                        'CreDtTm',
                                                        "test"
                                                    );
        $groupHeaderTag->appendChild($creationDateTime);
        $groupHeaderTag->appendChild($builder->doc->createElement('NbOfTxs', $this->transactionNumber));
        $groupHeaderTag->appendChild(
            $builder->doc->createElement('CtrlSum', $this->controlPrice)
        );

        $initiatingParty        = $builder->doc->createElement('InitgPty');
        $initiatingPartyName    = $builder->doc->createElement('Nm', $this->debtorName);
        $initiatingParty->appendChild($initiatingPartyName);
        if ($this->ref !== null) {
            $id = $builder->doc->createElement('Id', $this->ref);
            $initiatingParty->appendChild($id);
        }
        $groupHeaderTag->appendChild($initiatingParty);
        $builder->root->appendChild($groupHeaderTag);
        $builder->root->appendChild($groupHeaderTag);

        //Transaction traitment
        $this->currentPayment = $builder->doc->createElement('PmtInf');
        $this->currentPayment->appendChild($builder->doc->createElement('PmtInfId', $this->ref));
        $this->currentPayment->appendChild($builder->doc->createElement('PmtMtd', "TRF"));
        $this->currentPayment->appendChild(
            $builder->doc->createElement('NbOfTxs', $this->transactionNumber++)
        );
        $this->currentPayment->appendChild(
            $builder->doc->createElement('CtrlSum', $this->controlPrice)
        );

        $paymentTypeInformation = $builder->doc->createElement('PmtTpInf');
        $instructionPriority = $builder->doc->createElement('InstrPrty', "NORM");
        $paymentTypeInformation->appendChild($instructionPriority);
        $this->currentPayment->appendChild($paymentTypeInformation);

        $this->currentPayment->appendChild($builder->doc->createElement('ReqdExctnDt', "TEST"));
        $debtor = $builder->doc->createElement('Dbtr');
        $debtor->appendChild($builder->doc->createElement('Nm', "Societe S"));
        $this->currentPayment->appendChild($debtor);

        $debtorAccount = $builder->doc->createElement('DbtrAcct');
        $id = $builder->doc->createElement('Id');
        $id->appendChild($builder->doc->createElement('IBAN', "AZERTYUIOP"));
        $debtorAccount->appendChild($id);
        $this->currentPayment->appendChild($debtorAccount);

        $debtorAgent = $builder->doc->createElement('DbtrAgt');
        $financialInstitutionId = $builder->getFinancialInstitutionElement("BIC");
        $debtorAgent->appendChild($financialInstitutionId);
        $this->currentPayment->appendChild($debtorAgent);

        $builder->root->appendChild($this->currentPayment);
        foreach($this->payments as $payment)
        {
            /*
            $paymentTypeInformation = $builder->doc->createElement('PmtTpInf');
            $instructionPriority = $builder->doc->createElement('InstrPrty', "NORM");
            $paymentTypeInformation->appendChild($instructionPriority);
            $builder->root->appendChild($paymentTypeInformation);*/
        }

        //$builder->doc->createElement($this->currentPayment);


        return $builder->asXml();
    }
}