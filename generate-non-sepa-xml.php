<?php
	ini_set( 'error_reporting', E_ALL );
	ini_set( 'display_errors', 1 );
	ini_set( 'log_errors', 1 );

	require './vendor/autoload.php';

	use TransferFile\TransferFileCredit;
	$test = TransferFileCredit::createCustomerTransfer("MSG", "My society", "pain.001.001.03","Europe/Paris");
	$test->addPaymentInfo("ref-paiement-x", "My society", "FRXXXXXXXXXXXXXXXXXXX", "YYYYYYYYY");
	$test->createTransaction(200, 'USXXXXXXXXXXXXXXXXXXX', 'PPBPBP', ' USA Factory', 'Facture y', 'payement-x');
	echo $test->build();