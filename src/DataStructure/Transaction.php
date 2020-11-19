<?php
/**
 * Copyright (c) 19/11/2020 16:26 DIMBINIAINA Elkana Vinet
 * XML international transaction
 */

namespace DataStructure;


use Utils\StringHelpers;

class Transaction
{
    public function insertTransaction($document, $payment, $current)
    {
        //CrtTrfInf
        $CdtTrfTxInf            = $document->createElement('CdtTrfTxInf');
        $PmtId                  = $document->createElement('PmtId');
        //$PmtId->appendChild($document->createElement('InstrId', 'Virement 458B'));
        $PmtId->appendChild($document->createElement('EndToEndId', $payment['ref']));
        $CdtTrfTxInf->appendChild($PmtId);

        $amount                 = $document->createElement('Amt');
        $instructedAmount       = $document->createElement(
            'InstdAmt',
            $payment['amount']
        );
        $instructedAmount->setAttribute('Ccy', "USD");
        $amount->appendChild($instructedAmount);
        $CdtTrfTxInf->appendChild($amount);

        $creditorAgent          = $document->createElement('CdtrAgt');
        $financialInstitution   = $document->createElement('FinInstnId');
        $financialInstitution->appendChild($document->createElement('BIC', $payment['bic']));
        $creditorAgent->appendChild($financialInstitution);
        $CdtTrfTxInf->appendChild($creditorAgent);

        $creditor               = $document->createElement('Cdtr');
        $creditor->appendChild($document->createElement('Nm', $payment['name']));

        $CdtTrfTxInf->appendChild($creditor);

        $creditorAccount        = $document->createElement('CdtrAcct');
        $id = $document->createElement('Id');
        $id->appendChild($document->createElement('IBAN', $payment['iban']));
        $creditorAccount->appendChild($id);
        $CdtTrfTxInf->appendChild($creditorAccount);
        //Purp
        /*
        $purp                   = $document->createElement('Purp');
        $purp->appendChild($document->createElement('Cd', "SCVE"));
        $CdtTrfTxInf->appendChild($purp);
        //RgltryRptg

        $regist                 = $document->createElement('RgltryRptg');
        $reg                    = $document->createElement('Dtls');
        $reg->appendChild($document->createElement('Cd', '150'));
        $regist->appendChild($reg);
        $CdtTrfTxInf->appendChild($regist);*/
        //Remittance
        $remittanceInformation  = $document->createElement('RmtInf');
        $remittanceInformation->appendChild($document->createElement('Ustrd', StringHelpers::sanitizeString($payment['reason'])));
        $CdtTrfTxInf->appendChild($remittanceInformation);
        $current->appendChild($CdtTrfTxInf);
    }
}