<?php
// include sanitizer class
require_once 'Sanitizer.php';

// include formvalidator class
require_once 'FormValidator.class.php';

// init class
$form = new FormValidator();

// Fetch value from post type
$_POST['firstname'] = "Juan123";
$_POST['surname'] = "Dela Cruz123";
$_POST['age'] = "23";
$_POST['comment'] = "This is test comment's";
                
/*
 * (Optional)
 * Use to reset array to FormValidator class
 * if you have multple form entries
 */
$form->data = array();  

// Set and Hold data into array                                       
$form->setData($_POST);   

// set rules for First Name 
$form->setRules('firstname','First Name','required|alphaS');

// set rules for Surname                          
$form->setRules('surname','Surname','required|alphaS');   

// set rules for Age                  	
$form->setRules('age','Age','required|num');        

// set rules for Comment             	
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


?>