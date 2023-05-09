<?

if(empty($_GET) || !isset($_GET['token']))
{
    exit('token is missing');
}

require_once(__DIR__ . '/../static/php/validate_jwt.php');
$token = $_GET['token'];
$auth_status = validate_jwt($token);

if($auth_status['status'] == 'success')
{
    echo 'You have logged in successfully! The session will expire in a minute.<br>';
    var_dump($auth_status['payload']);
    /* wei: store cookie here. maybe refresh the lifetime whenever a request to the server is sent */
}
else if($auth_status['status'] == 'error')
{
    echo $auth_status['message'];
}