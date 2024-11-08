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

    try:
        response = sqs.receive_message(
            QueueUrl=queue_url,
            MaxNumberOfMessages=10,  
            WaitTimeSeconds=20 
        )

        messages = response.get('Messages', [])

        if messages:
            return {
                'statusCode': 200,
                'body': json.dumps(messages)
            }
        else:
            return {
                'statusCode': 200,
                'body': json.dumps('No messages available.')
            }

    except Exception as e:
        print(f"Error: {str(e)}")
        return {
            'statusCode': 500,
            'body': json.dumps(f"Error: {str(e)}")
        }
