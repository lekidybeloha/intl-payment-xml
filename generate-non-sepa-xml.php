<?php
	ini_set( 'error_reporting', E_ALL );
	ini_set( 'display_errors', 1 );
	ini_set( 'log_errors', 1 );

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