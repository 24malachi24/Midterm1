<?php
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$phone = $_POST['phone'];
$email = $_POST['email'];

# Checking for validity
$count = 0;

if (empty($fname)){
    ++$count;
    echo nl2br('Error ' . $count . "! First name cannot be empty...\n");
}
if (empty($lname)){
    ++$count;
    echo nl2br('Error ' . $count . "! Last name cannot be empty...\n");
}
if (empty($phone)){
    ++$count;
    echo nl2br('Error ' . $count . "! Phone number cannot be empty...\n");
}
if (empty($email)){
    ++$count;
    echo nl2br('Error ' . $count . "! Email address cannot be empty...\n");
}

# Checking for the length of fields
if (strlen($fname) > 20 ){
    ++$count;
    echo nl2br('Error ' . $count . "! Maximum length of the first name is 20...\n");
}
if (strlen($lname) > 20 ){
    ++$count;
    echo nl2br('Error ' . $count . "! Maximum length of the last name is 20...\n");
}
if (strlen($phone) > 12 ){
    ++$count;
    echo nl2br('Error ' . $count . "! Maximum length of the phone number is 12...\n");
}
if (strlen($email) > 30 ){
    ++$count;
    echo nl2br('Error ' . $count . "! Maximum length of the email is 30...\n");
}

# Checking first and last name 
if (!empty($fname) && !ctype_alpha($fname)){
    ++$count;
    echo nl2br('Error ' . $count . "! First name must be made up of alphabet characters only...\n");
}
if (!empty($lname) && !ctype_alpha($lname)){
    ++$count;
    echo nl2br('Error ' . $count . "! Last name must be made up of alphabet characters only...\n");
}

# Checking Phone number 
$phonePattern = '/\d{3}-\d{3}-\d{4}/';
if (!empty($phone)){
    if (!preg_match($phonePattern, $phone)){
        ++$count;
        echo nl2br('Error ' . $count . "! Phone number must be in format xxx-xxx-xxxx, where x is a digit...\n");
    }
}

# Checking Email Address 
function endsWith($strTosearch, $search)
{
    $searchLength = strlen($search);
    if (!$searchLength){
        return true;
    }
    return substr($strTosearch, -$searchLength) === $search;
}

if (!endsWith($email, '.com') && !endsWith($email, '.edu')){
    ++$count;
    echo nl2br('Error ' . $count . "! Email address must be either .com or .edu...\n");
}
if (substr_count($email, '@') != 1){
    ++$count;
    echo nl2br('Error ' . $count . "! Email address must contain a single @ sign...\n");
}
$atPos = strpos($email, '@'); # position of @
$dotPos = strpos($email, '.'); # position of .
$emailFirstPart = substr($email, 0, $atPos);
$emailSecPart = substr($email, $atPos + 1, $dotPos - $atPos - 1);
if (preg_match('/[^a-z]/i', $emailSecPart)){
    ++$count;
    echo nl2br('Error ' . $count . "! Email address @ must contain only letters...\n");
}

if ($count > 0 ) {?> 
    <a href="userInfo.html">Go Back</a>
<?php } else { # Data is validated
    $record = $lname . ':' . $fname . ':' . $phone . ':' . $email; 
    if (file_exists('userInfo.txt')){
        # Read contents only if the file exists
        $data = file_get_contents('userInfo.txt');
        $arr = explode("\n", $data);
        array_push($arr, $record);
        sort($arr); # Sorting the array 

        ($myFile = fopen('userInfo.txt', 'w')) or die('Can\'t open file!' );
        for ($i = 0; $i < count($arr); $i++){
            fwrite($myFile, $arr[$i]); # Write the current array element
            fwrite($myFile, "\n");
        }
        fclose($myFile);
        echo 'Data has been written to file userInfo.txt';
    } else{
        ($myFile = fopen('userInfo.txt', 'w')) or die ('Can\'t open file!');
        fwrite($myFile, $record);
        fclose($myFile);
        echo 'Data has been written to file userInfo.txt';
    }
}

# Display data in an HTML table sorted by last name
if (file_exists('userInfo.txt')) {
    $data = file_get_contents('userInfo.txt');
    $lines = explode("\n", $data);
    $records = [];
    
    foreach ($lines as $line) {
        $fields = explode(':', $line);
        if (count($fields) === 4) {
            $records[] = $fields;
        }
    }

    usort($records, function ($a, $b) {
        return strcasecmp($a[0], $b[0]);
    });

    echo '<table border="1">';
    echo '<tr><th>Last Name</th><th>First Name</th><th>Phone</th><th>Email</th></tr>';
    
    foreach ($records as $record) {
        echo '<tr>';
        echo '<td>' . $record[0] . '</td>';
        echo '<td>' . $record[1] . '</td>';
        echo '<td>' . $record[2] . '</td>';
        echo '<td>' . $record[3] . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
}
?>
<a href="userInfo.html">Go Back</a>