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
    echo 'You have logged in successfully!';
    var_dump($auth_status['payload']);
    /* wei: store cookie here */
}
else if($auth_status['status'] == 'error')
{
    echo $auth_status['message'];
}