<!DOCTYPE html>
<html>
<head>
    <title>Send Message to AWS SQS</title>
</head>
<body>
    <h2>Send Message to AWS SQS</h2>
    <!-- <form action="pushToSqs.php" method="post"> -->
    <form action="sendMessage.php" method="post">
        <label for="message">Enter Message:</label><br>
        <input type="text" id="message" name="message" required><br><br>
        <input type="submit" value="Send">
    </form>
</body>
</html>
