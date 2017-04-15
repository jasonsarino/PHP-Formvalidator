# PHP Formvalidator

This class is simple PHP Form validation can be use in your application.

## Usage
```
// include formvalidator class
require_once 'FormValidator.class.php';

// init class
$form = new FormValidator();

// Fetch value from post type
$_POST['firstname'] = 'Juan';
$_POST['surname'] = 'Dela Cruz';
$_POST['age'] = '23';
$_POST['comment'] = 'test comment';

// Set and get all data from $_POST
$form->setData($_POST);

/**
*  Set rules and message for every $_POST
*  @param string $_POST Name of text input
*  @param string Caption or label
*  @param array reserved form validation method 
*  @param string Error Messages
*/
$form->setRules('firstname', 'First Name', 'required|alphaS', 'Letters, space only');
$form->setRules('surname', 'Surname', 'alphaS', 'Letters, space only');
$form->setRules('age', 'Age', 'num', 'Numbers only');
$form->setRules('comment', 'Comment', 'alphaS', 'Letters, space only');

// Validate $_POST
$form->validateData();

// Display error messages
if ($form->isValid() > 0) {
    echo $form->getErrorMessage();
} else {
    $firstname = $form->dataFields['firstname'];
    $surname = $form->dataFields['surname'];
    $age = $form->dataFields['age'];
    $comment = $form->dataFields['comment'];

    echo "First Name: " . $firstname . "<br />Surname: " . $surname . "<br />Age: " . $age . "<br />Comment: " . $comment;
}
```
