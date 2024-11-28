#!/bin/bash

workspace=${1}
aws_region=${2}
taskFamily=${3}
clusterName=${4}
serviceName=${5}
img_tag=${6}
ecrRepo=${7}
mghAppName=${8}
cdAppName=${9}
cdGroupName=${10}

#Get the old revision number 
oldRevision=$(aws ecs describe-task-definition --task-definition $taskFamily  --region $aws_region  | egrep 'revision' | tr ',' ' '  | awk '{print $2}')  

#get the current task arn and token 
currentTaskArn=$(aws ecs list-tasks --cluster $clusterName  --family  $taskFamily  --output text  --region $aws_region  | egrep 'TASKARNS' | awk '{print $2}')

#copy json template to current working directory

sed -i "s|~img_tag~|$img_tag|g" $workspace/CICD_Script/Templates/mgh49_prod_task_defination.json
sed -i "s|~ecrRepo~|$ecrRepo|g" $workspace/CICD_Script/Templates/mgh49_prod_task_defination.json
sed -i "s|~mghapp~|$mghAppName|g" $workspace/CICD_Script/Templates/mgh49_prod_task_defination.json
sed -i "s|~taskfamily~|$taskFamily|g" $workspace/CICD_Script/Templates/mgh49_prod_task_defination.json

#launch service by using task defination template
aws ecs register-task-definition  --family $taskFamily --cli-input-json  file://$workspace/CICD_Script/Templates/mgh49_prod_task_defination.json  --region $aws_region 

#get the new Revison number 
newRevision=$(aws ecs describe-task-definition --task-definition $taskFamily  --region $aws_region  | egrep 'revision' | tr ',' ' '  | awk '{print $2}')

sed -i "s|~APPNAME~|${cdAppName}|g" prod-create-deployment.json
sed -i "s|~DEPGROUPNAME~|${cdGroupName}|g" prod-create-deployment.json
sed -i "s|~TASK_DEFINATION~|${taskFamily}|g" prod-create-deployment.json
sed -i "s|~BUILD_NUMBER~|${newRevision}|g" prod-create-deployment.json

#update the service revison 
DEPLOYMENT_ID=$(aws deploy create-deployment \
--cli-input-json file://prod-create-deployment.json \
--region $aws_region --query "deploymentId" \
--output text \
)

echo "Deployment ID :  ${DEPLOYMENT_ID}"

echo "Waiting for deployment..."

aws deploy wait deployment-successful --deployment-id $DEPLOYMENT_ID --region $aws_region

aws deploy get-deployment --deployment-id $DEPLOYMENT_ID --region $aws_region

DEPLOYMENT_STATUS=$(aws deploy get-deployment \
--deployment-id $DEPLOYMENT_ID \
--query "deploymentInfo.status" \
--output text \
--region $aws_region
)

echo "Deployment Status :  ${DEPLOYMENT_STATUS}"

([ "${DEPLOYMENT_STATUS}" = "Succeeded" ]) &&  exit 0 || exit 1