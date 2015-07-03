<?php

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
				$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = trim($_POST["message"]);

        // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }

        // Set the recipient email address.
        $recipient = "markl.nz70@gmail.com";

        // Set the email subject.
        $subject = "New message from $name on your website";

        // Build the email content.
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Message:\n$message\n";
		$user = 'markl.nz@hotmail.com';
		$pass = 'Mymsnpwd2';
		$host = 'https://smtp.live.com';
		$port = 465; 
		
        // Build the email headers.
        $email_headers = "From: $name <$email>";

        // Send the email.
        // if (mail($recipient, $subject, $email_content, $email_headers)) {
		if (smtp_mail($recipient, $subject, $email_content, $user, $pass, $host, $port)) {
            // Set a 200 (okay) response code.
            http_response_code(200);
            echo "Thank You! Your message has been sent.";
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
            echo "Oops! Something went wrong and we couldn't send your message.";
        }

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

	function smtp_mail($to, $from, $message, $user, $pass, $host, $port)
	{
		if ($h = fsockopen($host, $port))
		{
			$data = array(
				0,
				"EHLO $host",
				'AUTH LOGIN',
				base64_encode($user),
				base64_encode($pass),
				"MAIL FROM: <$from>",
				"RCPT TO: <$to>",
				'DATA',
				$message
			);

			foreach($data as $c)
			{
				$c && fwrite($h, "$c\r\n");
				while(substr(fgets($h, 256), 3, 1) != ' '){}
			}

			fwrite($h, "QUIT\r\n");
			return fclose($h);
		}
	}
	
?>