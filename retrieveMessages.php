<?php
require 'aws/autoload.php';

use Aws\Lambda\LambdaClient;

$AWS_KEY = '';
$AWS_SECRET = '';
$queueUrl = 'https://sqs.eu-north-1.amazonaws.com/724772075947/test-queue';

$lambdaClient = new LambdaClient([
    'region' => 'eu-north-1',
    'version' => 'latest',
    'credentials' => [
        'key'    => $AWS_KEY,
        'secret' => $AWS_SECRET,
    ]
]);

try {
    $result = $lambdaClient->invoke([
        'FunctionName' => 'recieve-messages', 
        'InvocationType' => 'RequestResponse'
    ]);

    $payload = $result->get('Payload');
    echo "Raw Lambda response: " . $payload;

    $responsePayload = json_decode($payload, true);
    if (isset($responsePayload['body'])) {
        $messages = json_decode($responsePayload['body'], true);
        echo "<h2>Messages in the SQS Queue:</h2>";
        if (!empty($messages)) {
            foreach ($messages as $message) {
                echo "<pre>" . json_encode($message, JSON_PRETTY_PRINT) . "</pre>";
            }
        } else {
            echo "No messages available.";
        }
    } else {
        echo "Lambda response does not contain the expected 'body' field.";
    }
} catch (Exception $e) {
    echo "Error invoking Lambda: " . $e->getMessage();
}
?>
