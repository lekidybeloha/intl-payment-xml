# PHP NON SEPA FILE GENERATOR

**This is a library for PHP to generate XML file for International Transaction(Virement International ou NON SEPA).**

# Installation

### Composer

This library uses [Composer](https://getcomposer.org/) to make things easy.

Learn to use composer and add this to require section (in your composer.json) :

```"ladina/php-non-sepa-xml": "1.*@dev"```

# Example

```php 
use TransferFile\TransferFileCredit;
$test = TransferFileCredit::createCustomerTransfer("MSG", "My society", "pain.001.001.03");
$test->addPaymentInfo("ref-paiement-x", "My society", "FRXXXXXXXXXXXXXXXXXXX", "YYYYYYYYY");
$test->createTransaction(200, 'USXXXXXXXXXXXXXXXXXXX', 'PPBPBP', ' USA Factory', 'Facture y', 'payement-x');
echo $test->build();
```

# Explication

This code will initiate the basic information for transaction

```php
TransferFileCredit::createCustomerTransfer("identificationMessage", "Society Name", "pain.001.001.03");
```
This code  will create the debitor account

```php
addPaymentInfo("payementReference", "Society Name", "IBAN", "BIC");
```

This code will create de credit bank transfert to the beneficiary

```php
createTransaction("amount", "IBAN", "BIC", "Beneficiary", "Society Name", "Remittance information", "Payment ID");
```
