This is a library to generate XML file for international transaction.<br>
Usage example :<br>
use TransferFile\TransferFileCredit;<br>

$test = TransferFileCredit::createCustomerTransfer("MSG", "My society", "pain.001.001.03");<br>
$test->addPaymentInfo("ref-paiement-x", "My society", "FRXXXXXXXXXXXXXXXXXXX", "YYYYYYYYY");<br>
$test->createTransaction(200, 'USXXXXXXXXXXXXXXXXXXX', 'PPBPBP', ' USA Factory', 'Facture y', 'payement-x');<br>
echo $test->build();