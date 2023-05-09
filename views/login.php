<?
if(empty($_POST) || !isset($_POST['email'])){
    echo 'nothing here';
    exit();
}

require_once(__DIR__ . '/../static/php/generate_jwt.php');
require_once(__DIR__ . '/../static/php/mail.php');
require_once(__DIR__ . '/../static/php/conn.php');
$email = $_POST['email'];
$db = db_connect('admin');
/* check if user exists */
$sql_getUser = 'SELECT id FROM `users` WHERE email = ? LIMIT 1';
$stmt = $db->prepare($sql_getUser);
$stmt->bind_param("s", $email);
$stmt->execute();
$result_getUser = $stmt->get_result()->fetch_assoc();
if(!$result_getUser)
{
    $created = date("Y-m-d H:i:s");
    $sql_createUser = 'INSERT INTO `users` SET `email` = ?, `created` = ?';
    $stmt_createUser = $db->prepare($sql_createUser);
    $stmt_createUser->bind_param("ss", $email, $created);
    $executed = $stmt_createUser->execute();
    $user_id = $stmt_createUser->insert_id || $executed;
}
else $user_id = $result_getUser['id'];
if($user_id === false)
    exit('Error when inserting new user');

$token = generate_jwt($user_id, 60);
$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]/account?token=" . $token;
$domain = 'mail.teigerfoundation.org';
$msg['from'] = 'submissions@teigerfoundation.org';
$msg['to'] = $email;
$msg['bcc'] = 'weiwanghasbeenused@gmail.com';
$msg['subject'] = 'Your login url to teigerfoundation.org';
$msg['text'] = "*\n\nPlease access the apply page through the following url:";
$msg['text'] .= "\n\n" . $url;
$msg['text'] .= "\n\n*\n\nhttps://www.teigerfoundation.org";
$sent = mailgun($domain, $msg);

echo $sent ? 'The login url has been sent to ' . $email : 'An error happened while the system tried sending the login url . . .';
echo '<div><a href="/">Back</a></div>';
