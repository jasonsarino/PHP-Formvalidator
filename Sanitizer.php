<?php
/**
 * @package   Sanitizer Class
 * @author    Jayson P. Sarino <jasonsarino27@gmail.com>
 * @copyright 2018 Jayson P. Sarino
 * @version   1.0.0
 */
class Sanitizer
{
    /**
     * Holds all the available patterns
     * @var array $patternList
     */
    private $patternList = array(
        'address'                     => '/[^0-9a-zA-Z \-\,\'\&]/',
        'country'                     => '/[^0-9a-zA-Z \-]/',
        'date'                        => '/[^0-9\-\/]/',
        'email_address'               => '/[^0-9a-zA-Z\!\#\$\%\&\'\*\+\-\/\=\?\^\_\`\{\|\}\~\@\.\[\]]/',
        'fax_number'                  => '/[^0-9 \+\-\(\)]/',
        'ip_address'                  => '/[^0-9a-zA-Z\.\-\:\/\=]/',
        'letter_number'               => '/[^0-9a-zA-Z]/',
        'letter_number_space'         => '/[^0-9a-zA-Z ]/',
        'letter_number_space_symbol'  => '/[^0-9a-zA-Z \/\-\,\|\@]/',
        'letter_only'                 => '/[^a-zA-Z]/',
        'letter_space'                => '/[^a-zA-Z ]/',
        'letter_symbol'               => '/[^a-zA-Z \-\(\)\/]/',
        'name'                        => '/[^a-zA-Z \.\'\-]/',
        'mobile_number'               => '/[^0-9\+\(\)]/',
        'number_comma'                => '/[^0-9\,]/',
        'number_decimal'              => '/[^0-9\.]/',
        'number_decimal_comma'        => '/[^0-9\.\,]/',
        'number_decimal_comma_symbol' => '/[^0-9\.\,\+\-]/',
        'number_only'                 => '/[^0-9]/',
        'number_dashed_comma'         => '/[^0-9\,\-]/',
        'number_space'                => '/[^0-9 ]/',
        'phone_number'                => '/[^0-9 \+\-\(\)\/]/',
        'postcode'                    => '/[^0-9a-zA-Z \-]/',
        'state'                       => '/[^0-9a-zA-Z \-]/',
        'string'                      => '/[^0-9a-zA-Z \~\`\!\@\#\$\%\^\&\*\(\)\-\_\=\+\[\]\{\}\:\;\'\|\<\,\>\.\/\?]/',
        'suburb'                      => '/[^0-9a-zA-Z \-\']/',
        'username_password'           => '/[^0-9a-zA-Z!#$%()*+-.\/=\{|\}~]/'
    );
    
    /**
     * Holds the error messages
     * @var array $message
     */
    private $errMsg = array();

    /**
     * Capitalize the first letter of every word
     * @param  string $value The value to be converted into first letter capitalized
     * @return string
     */
    function capitalizeFirst($value) {
        $capitalizeFirst = ucwords(strtolower($value));

        // If there are MC in values like McKnight, McDonalds, McMahon
        if (strpos($capitalizeFirst, 'Mc') === 0) {
            $capitalizeFirst = 'Mc'. ucwords(substr($capitalizeFirst, 2, strlen($capitalizeFirst)));
        }

        return $capitalizeFirst;
    }

    /**
     * Capitalize the string
     * @param  string $value The value to be converted into uppercase
     * @return string
     */
    function capitalizeString($value) {
        return strtoupper(strtolower($value));
    }

    /**
     * Converts date to Australian format
     * @param  string $date The date to be converted
     * @return string
     */
    function dateToAU($date) {
        $au = explode('-', $date);

        return $au[2] .'/'. $au[1] .'/'. $au[0];
    }

    /**
     * Converts date to PHP format
     * @param  string $date The date to be converted
     * @return string
     */
    function dateToPHP($date) {
        $php = explode('/', $date);

        return $php[2] .'-'. $php[1] .'-'. $php[0];
    }
    
    /**
     * Shows the emulated SQL query in a PDO statement. What it does is just extremely simple, but powerful:
     * It combines the raw query and the placeholders. For sure not really perfect (as PDO is more complex than just
     * combining raw query and arguments), but it does the job.
     *
     * @param  string $rawSQL
     * @param  array  $parameters
     * @return string
     */
    function debugPDO($rawSQL, $parameters)
    {
        $keys = array();
        $values = $parameters;

        foreach ($parameters as $key => $value)
        {
            // Check if named parameters (':param') or anonymous parameters ('?') are used
            if (is_string($key)) {
                $keys[] = '/' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }

            // Bring parameter into human-readable format
            if (is_string($value)) $values[$key] = "'" . $value . "'";
            else if (is_array($value)) $values[$key] = implode(',', $value);
            else if (is_null($value)) $values[$key] = 'NULL';
        }

        /*
        echo "<br> [DEBUG] Keys:<pre>";
        print_r($keys);

        echo "\n[DEBUG] Values: ";
        print_r($values);
        echo "</pre>";
         */

        $rawSQL = preg_replace($keys, $values, $rawSQL, 1, $count);

        return $rawSQL;
    }
    
    /**
     * Generate random characters
     * @param  string $value     The value to be used
     * @param  int    $padLength How many times the padding will repeat
     * @param  string $char      The character to pad
     * @param  string $direction Where to pad the character STR_PAD_RIGHT, STR_PAD_LEFT, STR_PAD_BOTH
     * @param  int    $length    The length of the string
     * @param  string $possible  User defined random characters to be use (optional)
     * @return string
     */
    function genRandomChars($value, $padLength, $char, $direction, $length, $possible=null) {
        mt_srand((double) microtime() * 1000000);

        // If the possible character combinations are set
        if ($possible != null) {
            $possible = $possible;
        } else {
            $possible = '01234567890123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        // Padding
        $string = str_pad($value, $padLength, $char, $direction);

        while (strlen($string) < $length) {
            $string .= substr($possible, (mt_rand() % strlen($possible)), 1);
        }

        return $string;
    }
    
    /**
     * Hash the value
     * @param  string $value the value to be hashed
     * @return string
     */
    function hasher($value) {
        // Crypt
        $cVal = crypt(sha1(md5($value)), $value);
        
        return $cVal;	
    }
    
    /**
     * Generate encode ID
     * @param  string $value     The value to be used
     * @return string
     */
    function encodeID($ID, $wKey) {
        $randStrLeft = substr(str_shuffle(str_repeat("01234567890123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 3)), 0, 3); 
        $randStrRight = substr(str_shuffle(str_repeat("01234567890123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 3)), 0, 3);
        $string = $randStrLeft.$wKey.$randStrRight."ival".$ID;
        return $string;
    }
    
    /**
     * Generate decode ID
     * @param  string $value     The value to be used
     * @return string
     */
    function decodeID($value) {
        $value = explode("ival", $value);
        $wKey = $value['0'];
        $wKey =  substr($wKey, 0, -3);
        $wKey =  substr($wKey, 3);
        
        $data = array(
            'wKey'  =>  $wKey,
            'id'    =>  $value['1']);
        return $data;
    }
    
    /**
     * Set oKey Cookie
     * 
     * @param  string $oKey     The value to be set in cookie
     * @return string
     */
    function setOkeyCookie($oKey){                                               
        setcookie("oKey", "", time() - 3600);                                  // delete oKey cookie
        setcookie('oKey', $oKey, time() + (86400 * 30), "/");                  // set cookie oKey
        return $oKey;
    }

    /**
     * Maximum filtering
     * @param  string          $value      The value to be sanitized
     * @param  string          $filterVar  The type of PHP built-in sanitation to be used
     * @param  integer|boolean $maxLen     The maximum string length the value should have. If set to FALSE, there will be no max length
     * @param  string          $filterPreg The preg_replace() function to be use defined within this class
     * @param  int             $flag       0=return only the filtered value, 1=returns value separated by 1v@Lc0r3
     * @param  string          $fieldName  The field where this filter is being use to
     * @return string|boolean
     */
    function maxFilter($value, $filterVar, $maxLen, $filterPreg, $flag=0, $fieldName='') {
        // Output parameters
        $outputParams = array();

        // Strip slashes after trimming the value
        $filtered = stripslashes(trim($value));

        // Use the PHP's built-in value sanitizer
        $filtered = $this->sanitizeItem($value, $filterVar);

        // Check the length of the value
        if (strlen($filtered) <= $maxLen || $maxLen == false) {
            // If the pattern exist in the pattern list
            if (isset($this->patternList[$filterPreg])) {
                $filterToUse = $this->patternList[$filterPreg];
            } else {
                $filterToUse = $filterPreg;
            }
            
            // Returns 0 if the pattern and value matched, else 1
            $outputParams['boolean'] = preg_match($filterToUse, $filtered);
            
            // Use the preg_replace
            $filtered = preg_replace($filterToUse, '', $filtered);

            // Get the sanitized value
            if ($flag == 1) {
                $flagged = array($filtered, $filterVar, $maxLen, $filterPreg);
                $outputParams['value'] = implode('1v@Lc0r3', $flagged);
            } else {
                $outputParams['value'] = $filtered;
            }

            // Return the field name
            if (trim($fieldName) != '') $outputParams['field_name'] = $fieldName;

            // Finally return the filtered message
            return $outputParams;
        } else {
            // Exceeds the given max character length
            return 2;
        }
    }
    
    /**
     * New maximum filtering
     * @param  array $params Set of values to be used in this method
     * @return array
     * 
     * Usage:
     *      maxFilterNew(array(
     *          'value'      => Value to be sanitized,
     *          'php_filter' => PHP built-in sanitation (url|int|ip|float|boolean|email),
     *          'max'        => Maximum character length,
     *          'pattern'    => The pattern to be use in the sanitizing,
     *          'flag'       => 0=return only the filtered value, 1=return value separated by 1v@Lc0r3,
     *          'message'    => Error message if the value did not matched the given pattern
     *          'field_name' => The field this method is being used
     *      ))
     */
    function maxFilterNew($params=array()) {
        // Value
        $value = isset($params['value']) ? $params['value'] : null;
        
        // PHP filter
        $phpFilter = isset($params['php_filter']) ? $params['php_filter'] : null;
        
        // Maxlen
        $maxLen = isset($params['max']) ? $params['max'] : 0;
        
        // Pattern
        $pattern = isset($params['pattern']) ? $params['pattern'] : '';
        $origPattern = $pattern;
        
        // Flag
        $flag = isset($params['flag']) ? $params['flag'] : 0;
        
        // Field name
        $fieldName = isset($params['field_name']) ? strtolower($params['field_name']) : '';
        
        // Message
        $message = isset($params['message']) ? $params['message'] : 'Invalid '. $fieldName;
        
        // Output parameters
        $output = '';
        
        // Strip slashes after trimming the value
        $value = stripslashes(trim($value));
        
        // Use the PHP's built-in value sanitizer
        if ($phpFilter != null) { $value = $this->sanitizeItem($value, $phpFilter); }

        // Check the length of the value
        if (strlen($value) <= $maxLen || $maxLen == 0) {
            // If the pattern exist in the pattern list
            if (isset($this->patternList[$pattern])) {
                $pattern = $this->patternList[$pattern];
            } else {
                $pattern = $pattern;
            }
            
            $matchPattern = str_replace('/[^', '/^[', $pattern);
            $matchPattern = str_replace(']/', ']+$/', $matchPattern);
            
            // If the value matched the pattern
            if (preg_match($matchPattern, $value)) {
                // Use the preg_replace
                $value = preg_replace($pattern, '', $value);

                // Get the sanitized value
                if ($flag == 1) {
                    $flagged = array($value, $phpFilter, $maxLen, $origPattern);
                    $output = implode('1v@Lc0r3', $flagged);
                } else {
                    $output = $value;
                }

                // Return the sanitized message
                $this->errMsg = $output;
            } else {
                // Does not matched the given pattern
                $this->errMsg = array('error' => 1, 'message' => $message);
            }
        } else {
            // Exceeds the given max character length
            $this->errMsg = array('error' => 1, 'message' => 'Exceeds the given max character length in '. $fieldName);
        }
        
        return $this->errMsg;
    }

    /**
     * Replace characters in a string
     *
     * @param  string $find    The characters to look for
     * @param  string $replace The characters to replace the found items
     * @param  string $value   The string or any user input
     * @return string
     */
    function replaceChar($find, $replace, $value) {
        return str_replace($find, $replace, $value);
    }

    /**
     * Sanitize a single value according to type using the PHP built-in sanitize functions
     * @param  string                   $value The value to be sanitized
     * @param  string                   $type  The type of sanitation to be used
     * @return string|int|float|boolean
     */
    function sanitizeItem($value, $type) {
        $flags = null;

        switch ($type) {
            case 'url':
                $filter = FILTER_SANITIZE_URL;
            break;

            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
            break;

            case 'ip':
                $filter = FILTER_VALIDATE_IP;
            break;

            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                $flags  = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
            break;

            case 'boolean':
                $filter = FILTER_VALIDATE_BOOLEAN;
            break;

            case 'email':
                $value  = substr($value, 0, 254);
                $filter = FILTER_SANITIZE_EMAIL;
            break;

            default:
                $filter = FILTER_SANITIZE_STRING;
                $flags  = FILTER_FLAG_NO_ENCODE_QUOTES;
            break;

        }

        $output = filter_var($value, $filter, $flags);

        return $output;
    }

    /**
     * Value trimmer
     * @param  string $value The value to be trimmed
     * @return string
     */
    function trimmer($value) {
    	return stripslashes(trim($value));
    }
    
    
    /**
     * Website URL
     * @param  string $url The url address to be trimmed
     * @return string
     */
    function websiteURL($url) {
        // Remove the http:// and https://
        $websiteURL = ltrim($url, 'http://');
        $websiteURL = ltrim($websiteURL, 'https://');

        return $websiteURL;
    }
}
?>
