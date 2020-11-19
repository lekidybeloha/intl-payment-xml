<?php
/**
 * Copyright (c) 19/11/2020 16:25 DIMBINIAINA Elkana Vinet
 * XML international transaction
 */

namespace DataStructure;



use Utils\StringHelpers;

class Header
{

    protected $creationDate;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
    }

    public function build($document, $root, $MSGID, $transactionNumber, $debtorname, $price)
    {
        $groupHeaderTag         = $document->createElement('GrpHdr');
        $messageId              = $document->createElement('MsgId', $MSGID);
        $groupHeaderTag->appendChild($messageId);
        $creationDateTime       = $document->createElement(
            'CreDtTm',
            $this->creationDate->format('Y-m-d\TH:i:s\Z')
        );
        $groupHeaderTag->appendChild($creationDateTime);
        $groupHeaderTag->appendChild($document->createElement('NbOfTxs', $transactionNumber));
        $groupHeaderTag->appendChild(
            $document->createElement('CtrlSum', $price)
        );

        $initiatingParty        = $document->createElement('InitgPty');
        $initiatingPartyName    = $document->createElement('Nm', StringHelpers::sanitizeString($debtorname));
        $initiatingParty->appendChild($initiatingPartyName);
        $groupHeaderTag->appendChild($initiatingParty);
        $root->appendChild($groupHeaderTag);
        $root->appendChild($groupHeaderTag);
    }

    public function getFormattedDate()
    {
        return $this->creationDate->format('Y-m-d');
    }
}