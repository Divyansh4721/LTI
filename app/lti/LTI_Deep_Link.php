<?php

namespace IMSGlobal\LTI;

use \Firebase\JWT\JWT;

class LTI_Deep_Link
{

    private $registration;
    private $deployment_id;
    private $deep_link_settings;
    private $sub;

    public function __construct($registration, $deployment_id, $deep_link_settings, $sub)
    {
        $this->registration = $registration;
        $this->deployment_id = $deployment_id;
        $this->deep_link_settings = $deep_link_settings;
        $this->sub = $sub;
    }

    public function get_response_jwt($resources)
    {
        $data = "{}";
        $sub='';
        if (isset($this->deep_link_settings['data'])) {
            $data = $this->deep_link_settings['data'];
        }
        if (isset($this->sub)) {
            $sub = $this->sub;
        }
        $message_jwt = [
            "iss" => $this->registration->get_client_id(),
           // "aud" => [$this->registration->get_issuer()],
            "aud" => $this->registration->get_issuer(),
            "exp" => time() + 300,
            "iat" => time(),
            "nbf" => time()-100,
            "sub" => !empty($sub) ? $sub : '',
            "nonce" => 'nonce' . hash('sha256', random_bytes(64)),
            "https://purl.imsglobal.org/spec/lti/claim/deployment_id" => $this->deployment_id,
            "https://purl.imsglobal.org/spec/lti/claim/message_type" => "LtiDeepLinkingResponse",
            "https://purl.imsglobal.org/spec/lti/claim/version" => "1.3.0",
            "https://purl.imsglobal.org/spec/lti-dl/claim/content_items" => array_map(function ($resource) {
                return $resource->to_array();
            }, $resources),
            "https://purl.imsglobal.org/spec/lti-dl/claim/data" => $data,
        ];
        return JWT::encode($message_jwt, $this->registration->get_tool_private_key(), 'RS256', $this->registration->get_kid());
    }

    public function output_response_form($resources)
    {
        $jwt = $this->get_response_jwt($resources);
?>
        <form id="auto_submit" action="<?= $this->deep_link_settings['deep_link_return_url']; ?>" method="POST">
            <input type="hidden" name="JWT" value="<?= $jwt ?>" />
            <input type="submit" name="Go" />
        </form>
        <script>
            document.getElementById('auto_submit').submit();
        </script>
<?php
    }
}
?>