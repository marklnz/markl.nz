<?php
	require("./sendgrid-php/sendgrid-php.php");
    
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
		
        // Send the email.
        if (smtp_mail($recipient, $subject, $email_content)) {
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

	function smtp_mail($to, $from, $message)
	{
		$url = 'https://api.sendgrid.com/';
		$apikey = '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';

		// Create a new SendGrid using my API key
		$sendgrid = new SendGrid($apikey);
		
		// Create an email and send it
		$email = new SendGrid\Email();
		$email
			->addTo($to)
			->setFrom($from)
			->setSubject('New message from a visitor to http://markl.nz')
			->setText($message)
			->setHtml($message)
		;
		
		$res = $sendgrid->send($email);
		
		if ($res->getCode() == 200) {
			return true;
		} else {
			return false;
		}
	}
	
?>