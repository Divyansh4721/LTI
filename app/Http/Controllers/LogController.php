<?php

namespace App\Http\Controllers;

use App\Models\ResourceLog;
use Exception;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\Exceptions\CustomException;

class LogController extends Controller
{
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateLogReturnAssetId($idToken)
    {
        $jwt = $idToken;
        $localIP = getHostByName(getHostName());
        // Get parts of JWT.
        $jwtParts = explode('.', $jwt);
        if (count($jwtParts) !== 3) {
            // Invalid number of parts in JWT.
            $msg = "Invalid id_token, JWT must contain 3 parts";
            throw new CustomException($msg);
        }
        $resourceData = array();
        // Decode JWT headers.
        $resourceData['header'] = json_decode(JWT::urlsafeB64Decode($jwtParts[0]), true);
        // Decode JWT Body.
        $resourceData['body'] = json_decode(JWT::urlsafeB64Decode($jwtParts[1]), true);
        $targetLinkURI = $resourceData['body']['https://purl.imsglobal.org/spec/lti/claim/target_link_uri'];

        $resourceLinkID = $resourceData['body']['https://purl.imsglobal.org/spec/lti/claim/resource_link']['id'] ?? '';
        $resourceLinkTitle = $resourceData['body']['https://purl.imsglobal.org/spec/lti/claim/resource_link']['title']
            ?? '';
        $messageType = $resourceData['body']['https://purl.imsglobal.org/spec/lti/claim/message_type']
            ?? '';
        $resourceURL = $resourceData['body']['https://purl.imsglobal.org/spec/lti/claim/custom']['resourceid']
            ?? '';
        $roles = json_encode($resourceData['body']['https://purl.imsglobal.org/spec/lti/claim/roles'])
            ?? '';
        $launchPresentation = $resourceData['body']
        ['https://purl.imsglobal.org/spec/lti/claim/launch_presentation']['document_target']
            ?? '';
        $toolPlatform = $resourceData['body']
        ['https://purl.imsglobal.org/spec/lti/claim/tool_platform']['product_family_code']
            ?? '';
        $accountName = $resourceData['body']['https://purl.imsglobal.org/spec/lti/claim/tool_platform']['name']
            ?? '';
        $contextTitle = $resourceData['body']['https://purl.imsglobal.org/spec/lti/claim/context']['title']
            ?? '';
        $userId = $resourceData['body']['https://purl.imsglobal.org/spec/lti/claim/lti11_legacy_user_id']
            ?? '';
        $issuer = $resourceData['body']['iss'] ?? '';
        $clientId = $resourceData['body']['aud'] ?? '';
        $deploymentId = $resourceData['body']['https://purl.imsglobal.org/spec/lti/claim/deployment_id']
            ?? '';
        $nonce = $resourceData['body']['nonce'] ?? '';
        $sub = $resourceData['body']['sub'] ?? '';
        $ltiVersion = $resourceData['body']['https://purl.imsglobal.org/spec/lti/claim/version']
            ?? '';
        $placement = $resourceData['body']['https://www.instructure.com/placement']
            ?? '';
        $settings = $resourceData['body']
        ['https://purl.imsglobal.org/spec/lti-dl/claim/deep_linking_settings'] ?? '';
        $exp = $resourceData['body']['exp'] ?? '';
        
        $resourceLogModel = new ResourceLog();
        $resourceLogModel->resource_link_id = $resourceLinkID;
        $resourceLogModel->resource_link_title = $resourceLinkTitle;
        $resourceLogModel->message_type = $messageType;
        $resourceLogModel->lti_resource_link = urldecode($resourceURL);
        $resourceLogModel->roles = $roles;
        $resourceLogModel->launch_presentation = $launchPresentation;
        $resourceLogModel->tool_platform = $toolPlatform;
        $resourceLogModel->account_name = $accountName;
        $resourceLogModel->context_title = $contextTitle;
        $resourceLogModel->user_id = $userId;
        $resourceLogModel->issuer = $issuer;
        $resourceLogModel->client_id = $clientId;
        $resourceLogModel->deployment_id = $deploymentId;
        $resourceLogModel->nonce = $nonce;
        $resourceLogModel->sub = $sub;
        $resourceLogModel->lti_version = $ltiVersion;
        $resourceLogModel->placement = $placement;
        $resourceLogModel->settings = json_encode($settings);
        $resourceLogModel->exp = $exp;
        $resourceLogModel->headers = $jwtParts[0];
        $resourceLogModel->payload = $jwtParts[1];
        $resourceLogModel->referred  = $localIP;
        $resourceLogModel->save();

        $targetLinkURIArr = explode('=', $targetLinkURI);
        if (count($targetLinkURIArr) == 2) {
            return $targetLinkURIArr[1];
        } else {
            return true;
        }
    }

}
