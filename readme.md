# PHP NON SEPA FILE GENERATOR

**This is a library for PHP to generate XML file for International Transaction(Virement International ou NON SEPA).**

# Installation

### Composer

This library uses [Composer](https://getcomposer.org/) to make things easy.

Learn to use composer and add this to require section (in your composer.json) :

```
"vsoft/intl-payment-xml":"1.*@dev"
```

# Example

```php 
<?php
use TransferFile\TransferFileCredit;

$test = TransferFileCredit::createCustomerTransfer(
	"uniqueID",
	"My society",
	"pain.001.001.03",
	"Europe/Paris"
);

$test->addPaymentInfo("ref-paiement-x",[
    "debtorName" => "My society",
    "debtorIBAN" => "FI1350001540000056",
    "debtorBIC" => "PSSTFRPPMON"
]
);

$test->createTransaction('payement-x',[
    "amount" => 500,
    "creditorIBAN" => "FI1350001540000056",
    "creditorAccountNumber" => "12345789012",
    "creditorBIC" => "OKOYFIHH",
    "creditorName" => "creditorName",
    "reason" => "reason phrase"
]);

echo $test->build(true);

```

# Explication

This code will initiate the basic information for transaction

```php
$test = TransferFileCredit::createCustomerTransfer(
    "uniqueID",
    "My society",
    "pain.001.001.03",
    "Europe/Paris"
);
```
This code  will create the debitor account

```php
$test->addPaymentInfo("ref-paiement-x",[
    "debtorName" => "My society",
    "debtorIBAN" => "FI1350001540000056",
    "debtorBIC" => "PSSTFRPPMON"
]
);
```

This code will create de credit bank transfert to the beneficiary

```php
$test->createTransaction('payement-x',[
    "amount" => 500,
    "creditorIBAN" => "FI1350001540000056",
    "creditorAccountNumber" => "12345789012",
    "creditorBIC" => "OKOYFIHH",
    "creditorName" => "creditorName",
    "reason" => "reason phrase"
]);
```
