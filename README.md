**This is a library to generate XML file for international transaction.**

# Usage example

```php 
use TransferFile\TransferFileCredit;
$test = TransferFileCredit::createCustomerTransfer("MSG", "My society", "pain.001.001.03");
$test->addPaymentInfo("ref-paiement-x", "My society", "FRXXXXXXXXXXXXXXXXXXX", "YYYYYYYYY");
$test->createTransaction(200, 'USXXXXXXXXXXXXXXXXXXX', 'PPBPBP', ' USA Factory', 'Facture y', 'payement-x');
echo $test->build();
```

# Explication

`TransferFileCredit::createCustomerTransfer(identificationMessage, Society Name, "pain.001.001.03")`

This code will initiate the basic information for transaction
`addPaymentInfo(payementReference, Society Name, IBAN, BIC)`<br>

This code  will create the debitor account

`createTransaction(amount, IBAN, BIC, Beneficiary Society Name, Remittance information, Payment ID)`

This code will create de credit bank transfert to the beneficiary