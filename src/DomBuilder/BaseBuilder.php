<?php
/**
 * Copyright (c) 19/11/2020 16:27 DIMBINIAINA Elkana Vinet
 * XML international transaction
 */

namespace DomBuilder;


class BaseBuilder
{
    public $doc;
    public $root;
    protected $painFormat;

    public function __construct($painFormat, $withSchemaLocation = true)
    {
        $this->painFormat           = $painFormat;
        $this->doc                  = new \DOMDocument('1.0', 'UTF-8');
        $this->doc->formatOutput    = true;
        $this->root                 = $this->doc->createElement('Document');
        $this->root->setAttribute('xmlns', sprintf('urn:iso:std:iso:20022:tech:xsd:%s', $painFormat));
        $this->doc->appendChild($this->root);
    }

    public function asXml()
    {
        return $this->doc->saveXML();
    }

    public function getFinancialInstitutionElement(?string $bic): \DOMElement
    {
        $finInstitution = $this->doc->createElement('FinInstnId');

        if ($bic === null) {
            $other = $this->doc->createElement('Othr');
            $id = $this->doc->createElement('Id', 'NOTPROVIDED');
            $other->appendChild($id);
            $finInstitution->appendChild($other);
        } else {
            $finInstitution->appendChild($this->doc->createElement('BIC', $bic));
        }

        return $finInstitution;
    }
}