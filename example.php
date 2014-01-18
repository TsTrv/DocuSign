<?php require_once('class.docuSign.php');

$docuSign=new DocuSign();

// Set propper values for DocuSign Developer
$docuSign->setIntegratorKey('BNAM-0025a7c5-dbbb-4324-ba0d-86cca9119cac');
$docuSign->setUsername('toso.trajanov@gmail.com');
$docuSign->setPassword('tt2889');

//Check if the user is connected
$docuSign->checkUser();

//Send Signature Request to Jon Doe
$docuSign->docusign('jondoe@gmail.com','Jon Doe');

// Check if Jon Doe signed the envelope
$docusign->checkSignatureStatus('jondoe@gmail.com', 'envelopeID');

 ?>