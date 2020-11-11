<?php


namespace DomBuilder;


class BaseBuilder
{
    protected $doc;
    protected $root;
    protected $painFormat;

    public function __construct($painFormat, $withSchemaLocation = true)
    {
        $this->painFormat = $painFormat;
        $this->doc = new \DOMDocument('1.0', 'UTF-8');
        $this->doc->formatOutput = true;
        $this->root = $this->doc->createElement('Document');
        $this->root->setAttribute('xmlns', sprintf('urn:iso:std:iso:20022:tech:xsd:%s', $painFormat));
        //$this->root->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        /*
        if ($withSchemaLocation) {
            $this->root->setAttribute('xsi:schemaLocation', "urn:iso:std:iso:20022:tech:xsd:$painFormat $painFormat.xsd");
        }*/
        $this->doc->appendChild($this->root);
    }
}