# PHP Formvalidator

This library use to validate and sanitize form entry with sanitization to avoid sql injection and easy to set rules for specific fields.

## Usage 

#### Include Sanitizer and FormValidator Class
```
// include sanitizer class
require_once 'Sanitizer.php';

// include formvalidator class
require_once 'FormValidator.class.php';

// init class
$form = new FormValidator();
```

### Fetch data from $_POST form
assume that we have data from $_POST data
```
// Fetch value from post type
$_POST['firstname'] = "Juan";
$_POST['surname'] = "Dela Cruz";
$_POST['age'] = "23";
$_POST['comment'] = "This is test comment's";
```

### Reset data array for multiple form entries
(Optional) Use to reset array to FormValidator class f you have multple form entries
```
$form->data = array(); 
```

### Set and Hold $_POST into data array variable
```
$form->setData($_POST);
```

### Set Rules ro fields
```
// set rules for First Name 
$form->setRules('firstname','First Name','required|alphaS');

// set rules for Surname                          
$form->setRules('surname','Surname','required|alphaS');   

// set rules for Age                  	
$form->setRules('age','Age','required|num');        

// set rules for Comment             	
$form->setRules('comment'  , 'Comment',   'alphaNumSymbolS');  
```

### Validate data array variable with rules
```
$form->validateData();	
```

### Get the result from validateData method
return bolean 
```
$form->isValid();
```

### Display Error
```
$form->getErrorMessage();
```

### Complete Usage
```
// include sanitizer class
require_once 'Sanitizer.php';

// include formvalidator class
require_once 'FormValidator.class.php';

// init class
$form = new FormValidator();

// Fetch value from post type
$_POST['firstname'] = "Juan";
$_POST['surname'] = "Dela Cruz";
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
    echo "First Name: " . $firstname . "<br />Surname: " . $surname . "<br />Age: " . $age . "<br />Comment: " . $comment;

} else {
	
	// Display all errors.
    echo  $form->getErrorMessage();
}
```
