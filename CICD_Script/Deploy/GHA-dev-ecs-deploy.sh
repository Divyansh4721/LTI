#!/bin/bash

cdAppName=$cdAppName
cdGroupName=$cdGroupName

workspace=${1}
#aws_region=${2}
aws_region=$aws_region
taskFamily=${3}
clusterName=${4}
serviceName=${5}
img_tag=${6}
ecrRepo=${7}
mghAppName=${8}
#cdAppName=${9}
#cdGroupName=${10}


#Get the old revision number 
oldRevision=$(aws ecs describe-task-definition --task-definition $taskFamily  --region $aws_region  | egrep 'revision' | tr ',' ' '  | awk '{print $2}')  

#get the current task arn and token 
currentTaskArn=$(aws ecs list-tasks --cluster $clusterName  --family  $taskFamily  --output text  --region $aws_region  | egrep 'TASKARNS' | awk '{print $2}')

#Stop the existing service and conatiner before launching the new one 
#aws ecs update-service --cluster $clusterName --service $serviceName --task-definition $taskFamily --desired-count 0  --region $aws_region 
#aws ecs stop-task --cluster $clusterName --task $currentTaskArn  --region $aws_region 

#copy json template to current working directory
# cp $workspace/CICD_Script/Templates/$taskDefinition mgh49_dev_task_defination.json
sed -i "s|~img_tag~|$img_tag|g" $workspace/CICD_Script/Templates/mgh49_dev_task_defination.json
sed -i "s|~ecrRepo~|$ecrRepo|g" $workspace/CICD_Script/Templates/mgh49_dev_task_defination.json
sed -i "s|~mghapp~|$mghAppName|g" $workspace/CICD_Script/Templates/mgh49_dev_task_defination.json
sed -i "s|~taskfamily~|$taskFamily|g" $workspace/CICD_Script/Templates/mgh49_dev_task_defination.json

#launch service by using task defination template
aws ecs register-task-definition  --family $taskFamily --cli-input-json  file://$workspace/CICD_Script/Templates/mgh49_dev_task_defination.json  --region $aws_region 

#get the new Revison number 
newRevision=$(aws ecs describe-task-definition --task-definition $taskFamily  --region $aws_region  | egrep 'revision' | tr ',' ' '  | awk '{print $2}')


#sed -i "s|~APPNAME~|${cdAppName}|g" dev-create-deployment.json
#sed -i "s|~DEPGROUPNAME~|${cdGroupName}|g" dev-create-deployment.json
#sed -i "s|~TASK_DEFINATION~|${taskFamily}|g" dev-create-deployment.json
#sed -i "s|~BUILD_NUMBER~|${newRevision}|g" dev-create-deployment.json

sed -i "s|~APPNAME~|${cdAppName}|g" $workspace/CICD_Script/Deploy/dev-create-deployment.json
sed -i "s|~DEPGROUPNAME~|${cdGroupName}|g" $workspace/CICD_Script/Deploy/dev-create-deployment.json
sed -i "s|~TASK_DEFINATION~|${taskFamily}|g" $workspace/CICD_Script/Deploy/dev-create-deployment.json
sed -i "s|~BUILD_NUMBER~|${newRevision}|g" $workspace/CICD_Script/Deploy/dev-create-deployment.json

#update the service revison 
#--cli-input-json file://dev-create-deployment.json \
DEPLOYMENT_ID=$(aws deploy create-deployment \
--cli-input-json file://$workspace/CICD_Script/Deploy/dev-create-deployment.json \
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
