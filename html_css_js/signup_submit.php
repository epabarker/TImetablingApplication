<?php
/*** begin our session ***/
session_start();
    
/*** first check that both the username, password and have been sent ***/
if((empty( $_POST['username']) || empty($_POST['password']) || empty($_POST['first_name']) || empty($_POST['last_name'])))
{
    $message = 'Please enter a valid username, password, first name and last name';
}
/*** check the username is the correct length ***/
elseif (strlen( $_POST['username']) > 50 || strlen( $_POST['username']) < 1)
{
    $message = 'Incorrect Length for Username';
}
/*** check the password is the correct length ***/
elseif (strlen( $_POST['password']) > 40 || strlen( $_POST['password']) < 1)
{
    $message = 'Incorrect Length for Password';
}
/*** check the first_name is the correct length ***/
elseif (strlen( $_POST['first_name']) > 50 || strlen( $_POST['first_name']) < 1)
{
    $message = 'Incorrect Length for First Name';
}
/*** check the last_name is the correct length ***/
elseif (strlen( $_POST['last_name']) > 50 || strlen( $_POST['last_name']) < 1)
{
    $message = 'Incorrect Length for Last Name';
}
/*** check the username has only alpha numeric characters ***/
elseif (ctype_alnum($_POST['username']) != true)
{
    /*** if there is no match ***/
    $message = "Username must be alpha numeric";
}
/*** check the password has only alpha numeric characters ***/
elseif (ctype_alnum($_POST['password']) != true)
{
        /*** if there is no match ***/
        $message = "Password must be alpha numeric";
}
/*** check the first_name has only alpha numeric characters ***/
elseif (ctype_alnum($_POST['first_name']) != true)
{
        /*** if there is no match ***/
        $message = "First Name must be alpha numeric";
}
/*** check the last_name has only alpha numeric characters ***/
elseif (ctype_alnum($_POST['last_name']) != true)
{
        /*** if there is no match ***/
        $message = "Last Name must be alpha numeric";
}
else
{
    /*** if we are here the data is valid and we can insert it into database ***/
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
	$first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
	$last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
	$contracted_hours = $_POST['contracted_hours'];
	$rank = $_POST['rank'];
    /*** now we can encrypt the password ***/
    $password = sha1( $password );
    
    /*** connect to database ***/
    /*** mysql hostname ***/
    $mysql_hostname = 'ebarker.uk.mysql';
    /*** mysql username ***/
    $mysql_username = 'ebarker_uk';
    /*** mysql password ***/
    $mysql_password = 'DCEc8USZjKpaUKdibfmanDwt';
    /*** database name ***/
    $mysql_dbname = 'ebarker_uk';
    try
    {
        $dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password);
        /*** $message = a message saying we have connected ***/
        /*** set the error mode to excptions ***/
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        /*** prepare the insert ***/
        $stmt = $dbh->prepare("INSERT INTO users (username, password, first_name, last_name, contracted_hours, rank) VALUES (:username, :password, :first_name, :last_name, :contracted_hours, :rank)");
        /*** bind the parameters ***/
        $stmt->bindParam(':username', $username, PDO::PARAM_STR, 50);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR, 40);
		$stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR, 50);
		$stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR, 50);
		$stmt->bindParam(':contracted_hours', $contracted_hours, PDO::PARAM_INT, 4);
		$stmt->bindParam(':rank', $rank, PDO::PARAM_STR, 6);
        /*** execute the prepared statement ***/
        $stmt->execute();
		
        /*** if all is done, head to home page ***/
        header("Location: /login.php");
		exit;
    }
    catch(Exception $e)
    {
        /*** check if the username already exists ***/
        if( $e->getCode() == 23000)
        {
            $message = 'Username already exists';
        }
        else
        {
            /*** if we are here, something has gone wrong with the database ***/
            $message = 'We are unable to process your request. Please try again later"';
        }
    }
}
?>
<?php echo $message; ?>
