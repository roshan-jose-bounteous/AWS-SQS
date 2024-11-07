<?php
require 'aws/autoload.php';

use Aws\Lambda\LambdaClient;

$AWS_KEY = '';
$AWS_SECRET = '';
$queueUrl = 'https://sqs.eu-north-1.amazonaws.com/724772075947/test-queue';

// Create Lambda client
$lambdaClient = new LambdaClient([
    'region' => 'eu-north-1',
    'version' => 'latest',
    'credentials' => [
        'key'    => $AWS_KEY,
        'secret' => $AWS_SECRET,
    ]
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message'])) {
    $message = $_POST['message'];

    try {
        $result = $lambdaClient->invoke([
            'FunctionName' => 'send-message',  // Replace with your actual Lambda function name
            'InvocationType' => 'RequestResponse',
            'Payload' => json_encode(['message' => $message])
        ]);

        // Print the raw result for debugging purposes
        echo "Raw Lambda response: " . $result->get('Payload');

        // Decode the response payload and check for 'body'
        $responsePayload = json_decode($result->get('Payload'), true);
        
        if (isset($responsePayload['body'])) {
            echo "Lambda response: " . $responsePayload['body'];
        } else {
            echo "Lambda response does not contain the expected 'body' field.";
        }
    } catch (Exception $e) {
        echo "Error invoking Lambda: " . $e->getMessage();
    }
} else {
    echo "No message provided!";
}
?>
