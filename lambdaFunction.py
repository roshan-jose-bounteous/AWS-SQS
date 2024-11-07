import boto3
import json
import os

AWS_ACCESS_KEY = ''
AWS_SECRET_KEY = ''

sqs = boto3.client(
    'sqs',
    region_name='eu-north-1',
    aws_access_key_id=AWS_ACCESS_KEY,
    aws_secret_access_key=AWS_SECRET_KEY
)

def lambda_handler(event, context):
    queue_url = os.environ.get('QUEUE_URL')
    if not queue_url:
        return {
            'statusCode': 500,
            'body': json.dumps('Error: QUEUE_URL environment variable is not set.')
        }
    
    message_body = event.get('message', 'Default message')
    
    try:
        response = sqs.send_message(
            QueueUrl=queue_url,
            MessageBody=message_body
        )
        print(f"Message sent with ID: {response['MessageId']}")
        return {
            'statusCode': 200,
            'body': json.dumps(f"Message sent with ID: {response['MessageId']}, queueUrl: {queue_url}, MessageBody: {message_body}")
        }
    except Exception as e:
        print(f"Error: {str(e)}")
        return {
            'statusCode': 500,
            'body': json.dumps(f"Error: {str(e)}")
        }
