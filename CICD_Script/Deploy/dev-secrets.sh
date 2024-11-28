#!/bin/bash

AWS_SECRET_ID_env="agf-medicalaccess-ltiapp-dev-env"
#AWS_SECRET_ID_env_testing="agf-medicalaccess-ltiapp-dev-unittesting-env"
aws_region="us-east-1"

# Ensure your EC2 instance has the most recent version of the AWS CLI
chmod 666 ../../.env && chmod 666 ../../.env.testing

# Export the secret to .env
aws secretsmanager get-secret-value --secret-id ${AWS_SECRET_ID_env} --region $aws_region --query SecretString --output text | jq -r 'to_entries|map("\(.key)=\(.value|tostring)")|.[]' > ../../.env
	
# Export the secret to .env.testing
#aws secretsmanager get-secret-value --secret-id ${AWS_SECRET_ID_env_testing} --region $aws_region --query SecretString --output text | jq -r 'to_entries|map("\(.key)=\(.value|tostring)")|.[]' > ../../.env.testing
  
#chmod 644 ../../.env && chmod 644 ../../.env.testing





##WORKSPACE=$1
##AWS_REGION=$2
#Retrieve the secret from AWS Secrets Manager
#aws secretsmanager get-secret-value \
##    --region "$AWS_REGION" \
##    --secret-id "agf-medicalaccess-ltiapp-dev-env" \
##    --query SecretString \
##    --output text | jq -r 'to_entries|map("\(.key)=\(.value|tostring)")|.[]' > "$WORKSPACE/.env"
    
    
    


