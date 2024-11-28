<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecurityHeadersMiddleware
{
    private $expectedHosts = [];

    public function handle(Request $request, Closure $next)
    {
        // Pass the request to the next middleware/handler
        $response = $next($request);

        // Fetch LTI platforms from the database
        $ltiPlatforms = DB::table('lti_platforms')
            ->whereNull('deleted_at')
            ->select('authorization_url', 'jwkseturl', 'access_token')
            ->get();

        $csp = "";
        $platformDomainArray = [];

        if($ltiPlatforms->isNotEmpty()){
            // Build the platform domain array for CSP
            foreach ($ltiPlatforms as $platform) {
                // Authorization Url
                $authorizationUrl = parse_url($platform->authorization_url);

                if(!empty($authorizationUrl['host']) && !empty($authorizationUrl['scheme'])){
                    $authorizationUrlHostParts = array_reverse(explode('.', $authorizationUrl['host']));

                    // Generate wildcard and full domain for Authorization Url
                    if(array_key_exists(0, $authorizationUrlHostParts) && array_key_exists(1, $authorizationUrlHostParts)){
                        $platformDomainArray[] = "{$authorizationUrl['scheme']}://*.{$authorizationUrlHostParts[1]}.{$authorizationUrlHostParts[0]}";
                        $platformDomainArray[] = "{$authorizationUrl['scheme']}://{$authorizationUrlHostParts[1]}.{$authorizationUrlHostParts[0]}";
                    }
                }

                // JWKset Url
                $jwksetUrl = parse_url($platform->jwkseturl);
                
                if(!empty($jwksetUrl['host']) && !empty($jwksetUrl['scheme'])){
                    $jwksetUrlHostParts = array_reverse(explode('.', $jwksetUrl['host']));

                    // Generate wildcard and full domain for JWKset Url
                    if(array_key_exists(0, $jwksetUrlHostParts) && array_key_exists(1, $jwksetUrlHostParts)){
                        $platformDomainArray[] = "{$jwksetUrl['scheme']}://*.{$jwksetUrlHostParts[1]}.{$jwksetUrlHostParts[0]}";
                        $platformDomainArray[] = "{$jwksetUrl['scheme']}://{$jwksetUrlHostParts[1]}.{$jwksetUrlHostParts[0]}";
                    }
                }

                // Access Token Url
                $accessTokenUrl = parse_url($platform->access_token);
                
                if(!empty($accessTokenUrl['host']) && !empty($accessTokenUrl['scheme'])){
                    $accessTokenUrlHostParts = array_reverse(explode('.', $accessTokenUrl['host']));

                    // Generate wildcard and full domain for Access Token Url
                    if(array_key_exists(0, $accessTokenUrlHostParts) && array_key_exists(1, $accessTokenUrlHostParts)){
                        $platformDomainArray[] = "{$accessTokenUrl['scheme']}://*.{$accessTokenUrlHostParts[1]}.{$accessTokenUrlHostParts[0]}";
                        $platformDomainArray[] = "{$accessTokenUrl['scheme']}://{$accessTokenUrlHostParts[1]}.{$accessTokenUrlHostParts[0]}";
                    }
                }
            }
        }

        $platformDomainArray[] = config('app.url');

        $this->expectedHosts = $platformDomainArray;

        // Remove duplicates and concatenate domains for CSP
        $csp = implode(' ', array_unique($platformDomainArray));

        $cspDefaultSrcimplode = implode(' ', array_unique(config('constant.cspDefaultSrc')));
        $cspScriptSrcimplode = implode(' ', array_unique(config('constant.cspScriptSrc')));
        $cspFontSrcimplode = implode(' ', array_unique(config('constant.cspFontSrc')));
        $cspStyleSrcimplode = implode(' ', array_unique(config('constant.cspStyleSrc')));
        $cspImgSrcimplode = implode(' ', array_unique(config('constant.cspImgSrc')));
        $cspFormActionimplode = implode(' ', array_unique(config('constant.cspFormAction'))); 
        
        // Set Content Security Policy
        $response->headers->set('Content-Security-Policy', "frame-ancestors 'self' {$csp}; default-src 'self' {$csp} {$cspDefaultSrcimplode}; script-src 'self' 'unsafe-eval' 'unsafe-inline' {$cspScriptSrcimplode}; font-src 'self' data: {$csp} {$cspFontSrcimplode};  style-src 'self' 'unsafe-inline' {$cspStyleSrcimplode} ; form-action 'self' {$csp} {$cspFormActionimplode}; object-src 'self' {$csp}; img-src 'self' data: {$cspImgSrcimplode};");
        
        $response->headers->set('Expect-CT', 'enforce, max-age=30');
        $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,PATCH,DELETE,OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type,Authorization,X-Requested-With,X-CSRF-Token');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $this->preventHostHeaderInjection($request);
        
        return $response;
    }

    private function preventHostHeaderInjection(Request $request): void
    {
        $trustedHost = [];

        if(!empty($this->expectedHosts)){
            $this->expectedHosts = array_unique($this->expectedHosts);

            foreach($this->expectedHosts as $url){

                if($request->url() !== $url){
                    $parsedUrl = parse_url($url);

                    $trustedHost[] = $parsedUrl['host'];
                }
                
            }
            
        }

        if (!empty($trustedHost) && !in_array($request->getHost(), $trustedHost)) {
            abort(403, 'Unauthorized Host');
        }
    }
}