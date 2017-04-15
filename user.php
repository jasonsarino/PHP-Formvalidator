<?php
require_once 'FormValidator.class.php';

$form = new FormValidator();

$_POST['firstname'] = 'Juan';
$_POST['surname'] = 'Dela Cruz';
$_POST['age'] = '23';
$_POST['comment'] = 'test comment';

$form->setData($_POST);

$form->setRules('firstname', 'First Name', 'required|alphaS', 'Letters, space only');
$form->setRules('surname', 'Surname', 'alphaS', 'Letters, space only');
$form->setRules('age', 'Age', 'num', 'Numbers only');
$form->setRules('comment', 'Comment', 'alphaS', 'Letters, space only');

$form->validateData();

if ($form->isValid() > 0) {
    echo $form->getErrorMessage();
} else {
    echo 'Amazing!';
}

$firstname = $form->dataFields['firstname'];
?>