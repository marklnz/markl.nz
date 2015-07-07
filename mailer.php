<?php		
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
		
		// Send the email.
		$result = Smtp_mail($recipient, $email, $email_content);
		echo $result;
		if ($result) {
            // Set a 200 (okay) response code.
            http_response_code(200);
			exit;
        } else {
            // Set a 500 (internal server error) response code.
            http_response_code(500);
			exit;
        }
    } else {
        // Not a POST request, set a 403 (forbidden) response code. 
        http_response_code(403);
        exit;
    }

	function Smtp_mail($to, $from, $message)
	{
		$url = 'https://api.sendgrid.com/';
		$user = 'azure_bb61ea201ce638f4ea2aff64613c6fea@azure.com';
		$pass = 'Mysendgridpwd1';

		$params = array(
			'api_user'  => $user,
			'api_key'   => $pass,
			'to'        => $to,
			'subject'   => 'A message from a visitor to http://markl.nz',
			'html'      => $message,
			'text'      => $message,
			'from'      => $from,
		  );

		$request =  $url.'api/mail.send.json';

		// Generate curl request
		$session = curl_init($request);
		// Tell curl to use HTTP POST
		curl_setopt($session, CURLOPT_POST, TRUE);
		// Tell curl that this is the body of the POST
		curl_setopt($session, CURLOPT_POSTFIELDS, $params);
		// Tell curl not to return headers, but do return the response
		curl_setopt($session, CURLOPT_HEADER, FALSE);
		// Tell PHP not to use SSLv3 (instead opting for TLS)
		curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSV1_2);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);

		// obtain response
		$response = curl_exec($session);
		curl_close($session);

		// print everything out
		echo $response;
		return $response;
	}
