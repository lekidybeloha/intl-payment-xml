<?php
	ini_set( 'error_reporting', E_ALL );
	ini_set( 'display_errors', 1 );
	ini_set( 'log_errors', 1 );

	require './vendor/autoload.php';

	use TransferFile\TransferFileCredit;

	$test = TransferFileCredit::createCustomerTransfer(
		"uniqueID",
		"My society",
		"pain.001.001.03",
		"Europe/Paris"
	);

	$test->addPaymentInfo(
		"ref-paiement-x",[
			'debtorName' => "My society",
			'debtorIBAN' => "FI1350001540000056",
			'debtorBIC' => "PSSTFRPPMON"
		]
	);

	$test->createTransaction('payement-x',[
			'amount' => 500,
			'creditorIBAN' => 'FI1350001540000056',
			'creditorAccountNumber' => '12345789012',
			'creditorBIC' => 'OKOYFIHH',
			'creditorName' => 'creditorName',
			'reason' => 'reason phrase'
		]
	);

	echo $test->build(true);