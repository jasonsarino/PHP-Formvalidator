<?php

require './vendor/autoload.php';

$form = new \FormValidator\FormValidator();

// Fetch value from post type
$_POST['firstname'] = "John";
$_POST['surname'] = "Doe";
$_POST['age'] = 26;
$_POST['comment'] = "This is a test comment's";

$form->data = array();  

$form->setData($_POST);  

$form->setRules('firstname','First Name','required|alphaS');
$form->setRules('surname','Surname','required|alphaS');            	
$form->setRules('age','Age','required|num');             	
$form->setRules('comment'  , 'Comment',   'alphaNumSymbolS');

// Execute and validate data request rules
$form->validateData();		

// Check if valid all rules
if ($form->isValid()) {                 

	// Get sanitized $_POST from validator
    $firstname = $form->dataFields['firstname'];
    $surname = $form->dataFields['surname'];
    $age = $form->dataFields['age'];
    $comment = $form->dataFields['comment'];

    // Display $_POST data
    echo "First Name: " . $firstname . "<br />Surname: " . $surname . 
    "<br />Age: " . $age . "<br />Comment: " . $comment;

} else {

	// Display all errors.
    echo  $form->getErrorMessage();
}