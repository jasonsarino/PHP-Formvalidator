<?php
/**
 * Form Class
 */
class FormValidator {
    // Class variables
    protected $valid = 0;
    protected $error = array();
    protected $data = array();
    public $dataFields = array();
    
    /**
     * Check that fields do not have characters other than letters of alphabet
     * @param string $value   Value to be checked
     * @param string $message Message to show on false conditions
     */
    function alpha($value, $message) {
        $value = $this->trimmer($value);
        
    	if (preg_match('/^[a-zA-Z]+$/', $value)) {
            return $value;
        } else {
            $this->collectError(FALSE, $message);
        }
    }
    
    /**
     * Check that fields do not have characters other than numbers and space
     * @param int    $value   Value to be checked
     * @param string $message Message to show on false conditions
     */
    function alphaS($value, $message) {
        $value = $this->trimmer($value);
        
    	if (preg_match('/^[a-zA-Z ]+$/', $value)) {
            return $value;
        } else {
            $this->collectError(FALSE, $message);
        }
    }
    
    /**
     * Check that fields do not have characters other than letters and numbers
     * @param string $value   Value to be checked
     * @param string $message Message to show on false conditions
     */
    function alphaNum($value, $message) {
        $value = $this->trimmer($value);
        
    	if (preg_match('/^[0-9a-zA-Z]+$/', $value)) {
            return $value;
        } else {
            $this->collectError(FALSE, $message);
        }
    }
    
    /**
     * Check that fields do not have characters other than letters of alphabet, numbers and space
     * @param int    $value   Value to be checked
     * @param string $message Message to show on false conditions
     */
    function alphaNumS($value, $message) {
        $value = $this->trimmer($value);
        
    	if (preg_match('/^[0-9a-zA-Z ]+$/', $value)) {
            return $value;
        } else {
            $this->collectError(FALSE, $message);
        }
    }
    
    /**
     * Collect the invalid conditions
     * @param boolean $forValid Increments if there is an error
     * @param string  $forError Error messages
     */
    function collectError($forValid, $forError) {
        $this->error[] = $forError;
        
        if ($forValid == FALSE) { $this->valid = $this->valid + 1; }
    }
    
    /**
     * Return error message
     */
    function getErrorMessage() {
        $err = implode('<br />', $this->error);
        
        $this->error = array();
        
    	return $err;
    }
    
    /**
     * Check if form is valid
     */
    function isValid() {
        return $this->valid;
    }
    
    /**
     * Check that fields are not longer than a defined value
     * @param string $value   Value to be checked
     * @param string $message Message to show on false conditions
     */
    function maxLen($value, $max, $message) {
        $value = $this->trimmer($value);
        
    	if (strlen($value) > $max) {
            $this->collectError(FALSE, $message);
        } else {
            return $value;
        }
    }
    
    /**
     * Check that fields do not have characters other than numbers
     * @param int    $value   Value to be checked
     * @param string $message Message to show on false conditions
     */
    function num($value, $message) {
        $value = $this->trimmer($value);
        
    	if (is_numeric($value)) {
            return $value;
        } else {
            $this->collectError(FALSE, $message);
        }
    }

    function required ($value, $message) {
        $value = $this->trimmer($value);

        if ($value == "") {
            $this->collectError(FALSE, $message);
        } else {
            return $value;
        }
    }
    
    /**
     * Check that fields do not have characters other than numbers and space
     * @param int    $value   Value to be checked
     * @param string $message Message to show on false conditions
     */
    function numS($value, $message) {
        $value = $this->trimmer($value);
        
    	if (preg_match('/^[0-9 ]+$/', $value)) {
            return $value;
        } else {
            $this->collectError(FALSE, $message);
        }
    }
    
    /**
     * Set fields data from array
     */
    function setData($post=array()) {
        $this->dataFields = $post;
    }
    
    /**
     * Set field rules
     * @param array $post Value to be checked
     */
    function setRules($field, $label = '', $rules=array(), $errors=array()) {
        if (!is_array($rules)) {
            $rules = explode('|', $rules);
        }
        
        if (!is_array($errors)) {
            $errors = explode('|', $errors);
        }
        
        $this->data[$field] = array(
            'field'  => $field,
            'label'  => $label,
            'rules'  => $rules,
            'errors' => $errors,
            'value(DEBUG)' => $this->dataFields[$field]
        );
    }
    
    /**
     * Show fields data
     */
    function showData() {
        return $this->data;
    }
    
    /**
     * Validate fields data
     */
    function validateData() {
        foreach ($this->data as $row) {
        	for ($i = 0; $i < count($row['rules']); $i++) {
                $this->$row['rules'][$i]($this->dataFields[$row['field']], $row['label'] .' accepts '. strtolower($row['errors'][$i]));
            }
        }
    }
    
    /**
     * Value trimmer
     * @param  string $value The value to be trimmed
     * @return string
     */
    function trimmer($value) {
    	return stripslashes(trim($value));
    }
}