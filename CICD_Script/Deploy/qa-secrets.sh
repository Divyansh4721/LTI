#!/bin/bash

AWS_SECRET_ID_env="agf-medicalaccess-ltiapp-qa-env"

aws_region="us-east-1"

# Ensure your EC2 instance has the most recent version of the AWS CLI
chmod 666 ../../.env

# Export the secret to .env
aws secretsmanager get-secret-value --secret-id ${AWS_SECRET_ID_env} --region $aws_region --query SecretString --output text | jq -r 'to_entries|map("\(.key)=\(.value|tostring)")|.[]' > ../../.env
  
chmod 644 ../../.env
