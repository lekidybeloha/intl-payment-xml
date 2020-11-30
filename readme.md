# PHP NON SEPA FILE GENERATOR

**This is a library for PHP to generate XML file for International Transaction(Virement International ou NON SEPA).**

# Installation

### Composer

This library uses [Composer](https://getcomposer.org/) to make things easy.

Learn to use composer and add this to require section (in your composer.json) :

```php
"vsoft/intl-payment-xml":"1.*@dev"
```

# Example

```php 
require './vendor/autoload.php';

	use TransferFile\TransferFileCredit;

	$test = TransferFileCredit::createCustomerTransfer(
		"MSG",
		"My society",
		"pain.001.001.03",
		"Europe/Paris"
	);

	$test->addPaymentInfo(
		"ref-paiement-x",[
			'debtorName' => "My society",
			'debtorIBAN' => "FRXXXXXXXXXXXXXXXXXXX",
			'debtorBIC' => "YYYYYYYYY"
		]
	);

	$test->createTransaction('payement-x',[
			'amount' => 602,
			'creditorIBAN' => 'FR7630003632145698745632145',
			'creditorAccountNumber' => 'creditorAccountNumber',
			'creditorBIC' => 'creditorBIC',
			'creditorName' => 'creditorName',
			'reason' => 'reason'
		]
	);

	echo $test->build(true);
```

# Explication

This code will initiate the basic information for transaction

```php
$test = TransferFileCredit::createCustomerTransfer(
	"MSG",
	"My society",
	"pain.001.001.03",
	"Europe/Paris"
);
```
This code  will create the debitor account

```php
$test->addPaymentInfo(
	"payment-reference",[
		'debtorName' => "My society",
		'debtorIBAN' => "FRXXXXXXXXXXXXXXXXXXX",
		'debtorBIC' => "YYYYYYYYY"
	]
);
```

This code will create de credit bank transfert to the beneficiary

```php
$test->createTransaction('payement-reference',[
	'amount' => 602,
	'creditorIBAN' => 'FR7630003632145698745632145',
	'creditorAccountNumber' => 'creditorAccountNumber',
	'creditorBIC' => 'creditorBIC',
	'creditorName' => 'creditorName',
	'reason' => 'reason'
]
);
```
