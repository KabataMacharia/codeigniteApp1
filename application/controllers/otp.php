<?php
use telesign\sdk\messaging\MessagingClient;

$customer_id = "FBF971D8-454D-44A8-BAAE-34444A7EDCDD";
$api_key = "vdsJxNRsHlTWWZzJDXQopHeEWKvEmYab0BUXnCl6yytva5eksBKaHiq3+xcmR1st16RVZiR+c/R0tuqKSIsYcA==";

$phone_number = "phone_number";
$message = "You're scheduled for a dentist appointment at 2:30PM.";
$message_type = "ARN";

$messaging_client = new MessagingClient($customer_id, $api_key);
$response = $messaging_client->message($phone_number, $message, $message_type);

print_r($response->json);

					redirect('otp');
				}else{
					$this->session->set_userdata('error', 'Invalid username/password');
					redirect('login');
				}
			}
		}