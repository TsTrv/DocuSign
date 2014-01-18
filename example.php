<?php require_once('class.docuSign.php');

$docuSign=new DocuSign();

// Set propper values for DocuSign Developer
$docuSign->setIntegratorKey('Integrator Key');
$docuSign->setUsername('Email');
$docuSign->setPassword('Password');

//Check if the user is connected
$docuSign->checkUser();

//Send Signature Request to Jon Doe
$docuSign->docusign('jondoe@gmail.com','Jon Doe');

// Check if Jon Doe signed the envelope
$docusign->checkSignatureStatus('jondoe@gmail.com', 'envelopeID');

 ?>
