This is a library to generate XML file for international transaction.<br>

Usage example :<br>
use TransferFile\TransferFileCredit;<br>

$test = TransferFileCredit::createCustomerTransfer("MSG", "My society", "pain.001.001.03");<br>
$test->addPaymentInfo("ref-paiement-x", "My society", "FRXXXXXXXXXXXXXXXXXXX", "YYYYYYYYY");<br>
$test->createTransaction(200, 'USXXXXXXXXXXXXXXXXXXX', 'PPBPBP', ' USA Factory', 'Facture y', 'payement-x');<br>
echo $test->build();

<br>
<br>
<b>Explication</b><br>
TransferFileCredit::createCustomerTransfer(identificationMessage, Society Name, "pain.001.001.03")<br>
This code will initiate the basic information for transaction
<br><br>
addPaymentInfo(payementReference, Society Name, IBAN, BIC)<br>
This code  will create the debitor account
<br><br>
createTransaction(amount, IBAN, BIC, Beneficiary Society Name, Remittance information, Payment ID)<br>
This code will create de credit bank transfert to the beneficiary