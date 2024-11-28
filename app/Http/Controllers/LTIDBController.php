<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\PlatformKey;
use IMSGlobal\LTI;
use IMSGlobal\LTI\LTI_Deployment;
use IMSGlobal\LTI\LTI_Registration;
use Illuminate\Support\Facades\Session;
use Exception;

class LTIDBController implements LTI\Database
{
    /**
     *  Create login endpoint.
     */
    public function find_registration_by_issuer($iss)
    {
        try {
            $getRegistrationDetail = Platform::where('issuer', $iss)
                ->where('enabled', 1)->first();
            if (empty($getRegistrationDetail->jwkseturl)) {
                return false;
            }
            return LTI_Registration::new()
                ->set_issuer($getRegistrationDetail['issuer'])
                ->set_client_id($getRegistrationDetail['platform_client_id'])
                ->set_auth_login_url($getRegistrationDetail['authorization_url'])
                ->set_auth_token_url($getRegistrationDetail['access_token'])
                ->set_key_set_url($getRegistrationDetail['jwkseturl'])
                ->set_auth_server($getRegistrationDetail['issuer'])
                //->set_kid($kid)
                ->set_tool_private_key($getRegistrationDetail['private_key']);
            } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     *  Create deployment.
     */
    public function find_deployment($iss, $deploymentId)
    {
        $result = PlatformKey::where('deployment_id', $deploymentId)
            ->where('issuer', $iss)
            ->leftJoin('lti_platforms', 'lti_platforms.platform_id', '=', 'lti_platform_keys.platform_id')
            ->select('lti_platforms.platform_client_id', 'lti_platform_keys.deployment_id')->first();

        if (!isset($result) && !empty($result)) {
            return false;
        }

        $ltiDeployment = LTI_Deployment::new();
        $ltiDeployment->set_deployment_id($deploymentId);
        return $ltiDeployment;
    }

    public function find_registration_by_client($platformClientId)
    {
        try {
            $getRegistrationDetails = Platform::where('platform_client_id', $platformClientId)
                ->where('enabled', 1)->first();
            if (empty($getRegistrationDetails->jwkseturl)) {
                return false;
            }
            Session::put('instituteId', $getRegistrationDetails['mgh_client_id']);
            Session::put('logo', $getRegistrationDetails['logo']);
                return LTI_Registration::new()
                    ->set_issuer($getRegistrationDetails['issuer'])
                    ->set_client_id($getRegistrationDetails['platform_client_id'])
                    ->set_auth_login_url($getRegistrationDetails['authorization_url'])
                    ->set_auth_token_url($getRegistrationDetails['access_token'])
                    ->set_key_set_url($getRegistrationDetails['jwkseturl'])
                    ->set_auth_server($getRegistrationDetails['issuer'])
                    //->set_kid($kid)
                    ->set_tool_private_key($getRegistrationDetails['private_key']);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
