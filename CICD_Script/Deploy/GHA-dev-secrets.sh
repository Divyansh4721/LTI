#!/bin/bash

WORKSPACE=$1
AWS_REGION=$2
#Retrieve the secret from AWS Secrets Manager
aws secretsmanager get-secret-value \
    --region "$AWS_REGION" \
    --secret-id "agf-medicalaccess-ltiapp-dev-env" \
    --query SecretString \
    --output text | jq -r 'to_entries|map("\(.key)=\(.value|tostring)")|.[]' > "$WORKSPACE/.env"
    #--output text > "$WORKSPACE/.env"