<?php		
	require("/sendgrid-php/sendgrid-php.php");
	
	// Only process POST requests.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
		$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = trim($_POST["message"]);
		
	    // Set the recipient email address.
        $recipient = "markl.nz70@gmail.com";

        // Set the email subject.
        $subject = "New message from $name on your website";

		// Build the email content.
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Message:\n$message\n";
		
		$user = "azure_bb61ea201ce638f4ea2aff64613c6fea@azure.com";
		$pass = "";
		
		echo "From: " . $email . "\n";
		echo "Name: " . $name . "\n";
		echo "Message: " . $message . "\n";
		
		$sendgrid = new SendGrid($user, $pass);
		$email = new SendGrid\Email();
		$email
			->addTo($recipient)
			->setFrom("markl.nz@hotmail.com")
			->setFromName($name)
			->setSubject("A message from a visitor to http://markl.nz")
			->setText($email_content)
			->setHtml($email_content)
		;

		//Send, and catch any errors

		try {
			$sendgrid->send($email);
			http_response_code(200);
		} catch(\SendGrid\Exception $e) {
			echo $e->getCode() . "\n";
			foreach($e->getErrors() as $er) {
				echo $er;
			}
			http_response_code(500);
		}
		
    } else {
        // Not a POST request, set a 403 (forbidden) response code. 
        http_response_code(403);
        exit;
    }
	
	
	
	
	
	
	
