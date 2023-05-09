<?
    /*
        mailgun php sdk
        https://github.com/mailgun/mailgun-php
    */

    require 'vendor/autoload.php';
    use Mailgun\Mailgun;

    function mailgun($domain, $msg) {
        $mailgun_client_id = $_ENV['MAILGUN_CLIENT_ID'];
        if(!$mailgun_client_id) return false;
        try {
            $mg = Mailgun::create($mailgun_client_id);
            $mg->messages()->send($domain, [
    	        
                'from'	=> $msg['from'],
	            'to'	=> $msg['to'],
	            'bcc'	=> $msg['bcc'],
    	        'subject' => $msg['subject'],
	            'text'	=> $msg['text']
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
?>
