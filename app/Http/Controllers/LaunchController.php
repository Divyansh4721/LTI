<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\LTIContentType;
use App\Models\PlatformKey;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use IMSGlobal\LTI;
use IMSGlobal\LTI\JWKS_Endpoint;
use IMSGlobal\LTI\LTI_Deep_Link_Resource;
use IMSGlobal\LTI\LTI_Deployment;
use IMSGlobal\LTI\LTI_Message_Launch;
use IMSGlobal\LTI\LTI_OIDC_Login;
use IMSGlobal\LTI\LTI_Registration;
use IMSGlobal\LTI\OIDC_Exception;
use Firebase\JWT\JWT;
use phpseclib\Crypt\RSA;
use Ramsey\Uuid\Guid\Guid;
use App\Exceptions\CustomException;
use App\Http\Controllers\LTIDBController;
use App\Http\Controllers\LogController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LaunchController extends Controller
{
    private $assetPerPage;
    private $notAvailableLink;
    private $pointerEvents;

    public function __construct()
    {
        $this->assetPerPage = config('constant.asset_per_page');
        $this->notAvailableLink = config('constant.link_not_available');
        $this->pointerEvents = config('constant.pointer_events');
    }

    /**
     *  Index page for the launch with a view file.
     *  Using the validator for the OIDC connection.
     *  Responding with LTI_Message as per IMSGlobal.
     */
    public function launch(Request $request)
    {
        try {
            $return = '';
            $requestId = '';

            $contentTypes = $this->getContentTypes();

            $contentTypes = array_unique(array_reduce($contentTypes, function ($carry, $item) {
                return array_merge($carry, $item);
            }, []));

            $contentTypes['author'] = 'Author'; // static requirement from MGH to enable author based search

            sort($contentTypes);

            $contentTypesModified = [];

            foreach ($contentTypes as $value) {
                $key = Str::replace(' ', '+', $value);
                $contentTypesModified[$key] = $value;
            }

            $contentTypes = $contentTypesModified;

            $platformClientId = $request->platform_client_id;

            if (!isset($request->requestId) && empty($request->requestId)) {
                $launch = LTI_Message_Launch::new(new LTIDBController());
                $launch->validate();
            } else {
                $requestId = $request->requestId;
                $launch = LTI_Message_Launch::from_cache($requestId, new LTIDBController());
                if (!$launch->is_deep_link_launch()) {
                    $msg = "Must be a deep link!";
                    throw new CustomException($msg);
                }
            }

            if ($launch->is_resource_launch()) {
                $logController = new LogController();
                $resourceURL= $logController->generateLogReturnAssetId($request->id_token);
                $response = $this->launchResource($resourceURL);
                 if ($response == 'not_found') {
                     $errorMsg =  "The requested resouce has been removed.";
                    $return = view('error-resource', compact('errorMsg'));
                 }
            } elseif ($launch->is_deep_link_launch()) {
               if (!empty($request->id_token)) {
                    $logController = new LogController();
                    $logController->generateLogReturnAssetId($request->id_token);
                }
                $requestId = $launch->get_launch_id();

                $url = Config::get('constant.search_API.domain');
                $env = Config::get('app.env');
                $path = config('constant.search_API.path');
                $apiKey = config('constant.search_API.apiKey');
                $productFiltersData = $this->GetProductFilterList($url, $env, $path, $apiKey);
                $productFilters = (!empty($productFiltersData['SiteDetails'])) ? $productFiltersData['SiteDetails'] : [];
                usort(
                    $productFilters, fn(array $sort1, array $sort2): int => $sort1['SiteName'] <=> $sort2['SiteName']
                );
                $logo = Session::get('logo');

                // $return = view('launch', compact('requestId', 'contentTypes', 'productFilters', 'logo'));

                // Set Content Security Policy
                $csp = url('/');
                $return = response()
                ->view('launch', compact('requestId', 'contentTypes', 'productFilters', 'logo'), 200)
                ->header('Content-Security-Policy', "frame-ancestors 'self' {$csp};");

            } else {
                $errorMsg = 'Unknown launch type';
                $return = view('error', compact('platformClientId', 'errorMsg'));
            }
            return $return;
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            return view('error', compact('platformClientId', 'errorMsg'));
        }
    }

    private function launchResource($resourceUrl)
    {
        if (isset($resourceUrl) && !empty($resourceUrl)) {
            $resourceUrl = urldecode($resourceUrl);
            if (preg_match('/Firefox[\/\s](\d+\.\d+)/', $_SERVER["HTTP_USER_AGENT"])) {
                echo '<a href="'.$resourceUrl.'" target="_blank"><button type="button">Load in new window</button></a>';
            } else {
                echo "<script>window.open('".$resourceUrl."');history.back();</script>";
            }
        } else {
            return "Resource not found";
        }
    }

    public function apiResponse(Request $request)
    {
        $institutionId = $request->institutionId;
        $selectedAssets = $request->selectedAssets;
        $requestId = $request->request_id;
        $searchData = urlencode($request->search) ?? '';
        $flTopLevelContentDisplayName = urlencode($request->fl_TopLevelContentDisplayName) ?? '';
        $page = (!empty($request->page)) ? $request->page : 1;
        $bookId = $request->bookId ?? '';
        $title = $request->title ?? '';
        $flSiteID = $request->fl_SiteID;
        $chapterId = $request->chapterId;
        $contentType = $request->contentType;
        $sectionType = $request->sectionType ?? '';
        $oldBookId = $request->oldBookId ?? '';
        $oldTitle = $request->old_title ?? '';
        $response = $this->callApi($institutionId, $page, $flTopLevelContentDisplayName, $bookId, $chapterId, $sectionType, $flSiteID, $searchData);

        $responseArr = $response['SearchResults'] ?? '';
        $totalRecors = $response['TotalCount'] ?? 0;
        $source = (!empty($response['SearchResults'][0]['Source'])) ? str_replace("Book: ","",$response['SearchResults'][0]['Source']) : '';
        $recordPerPage = config('constant.record_per_page');
        $totalPages= ceil($totalRecors/$recordPerPage);
        $contentTypes = $this->getContentTypes();

        $returnHTML = view(
            'launch-content',
            compact(
                'responseArr',
                'totalRecors',
                'page',
                'requestId',
                'totalPages',
                'bookId',
                'oldBookId',
                "title",
                "oldTitle",
                'flTopLevelContentDisplayName',
                'flSiteID',
                'chapterId',
                'contentType',
                'sectionType',
                'selectedAssets',
                'source',
                'contentTypes'
                )
            )->render();
        
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }

    public function GetProductFilterList($url, $env, $path, $apiKey)
    {
        $institutionId = Session::get('instituteId');
        $finalAPIURL = $url.'/'.$path.'/'.$apiKey.'/sites';
        $curlResponse = $this->curl($finalAPIURL, $env, $institutionId);
        return json_decode($curlResponse, true);
    }

    public function callApi($institutionId, $page = 1, $contentType = null, $bookId = null, $chapterId = null, $sectionType = null, $siteId = null, $searchData = null)
    {
        $url = Config::get('constant.search_API.domain');
        $env = Config::get('app.env');
        $path = Config::get('constant.search_API.path');
        $apiKey = Config::get('constant.search_API.apiKey');

        $page = 'page='.$page;

        $fl_SiteId = !empty($siteId) ? '&fl_SiteID='.$siteId : null;
        $fl_BookID = !empty($bookId) ? '&fl_BookID='.$bookId : null;
        $parentSectionId = !empty($chapterId) ? '&exPrm_fq=SectionTypeName:*+AND+ParentSectionID:'.$chapterId : null;
        $q = !empty($searchData) ? (!empty($contentType) && ($contentType=='Author') ? '&author='.$searchData: '&q='.$searchData) : null;

        $fl_contentType = !empty($contentType) ? (($contentType!='Author') ? '&fl_TopLevelContentDisplayName='.$contentType : null )  : null;
        


        // static requirement from MGH to enable default selection for 187:AccessMedicina and 313:Accessartmed based on API dependency
        
        $filterBy = '';
        if(empty($searchData) && empty($contentType) && empty($bookId))
        {
            $displayName = '&fl_TopLevelContentDisplayName=';

            if (!empty($siteId)) {
                switch ($siteId) {
                    case '187':
                        $filterBy = $displayName . 'Libros';
                        break;
                    case '313':
                        $filterBy = $displayName . 'Livros';
                        break;
                    default:
                        $filterBy = '';
                        break;
                }
            } else {
                $filterBy = $displayName . 'Textbooks' . $displayName . 'Livros' . $displayName . 'Libros';
            }
        }

        $searchFields = 'search?'.$page.$q.$fl_SiteId.$fl_BookID.$parentSectionId.$fl_contentType.$filterBy;
        $finalAPIURL = $url.'/'.$path.'/'.$apiKey.'/'.$searchFields;

        $curlResponse = $this->curl($finalAPIURL, $env, $institutionId);        
        return json_decode($curlResponse, true);
    }

    public function getContentTypes()
    {
        $contentTypes = LTIContentType::all();

        $contentTypesRawData = [];

        if($contentTypes->isNotEmpty()){
            foreach($contentTypes as $contentType){
                $name = $contentType->name;
                $level = $contentType->level;

                if(!array_key_exists('l'.$level, $contentTypesRawData)){
                    $contentTypesRawData['l'.$level] = [$name];
                }else{
                    array_push($contentTypesRawData['l'.$level], $name);
                }

            }
        }

        return $contentTypesRawData;
    }

    public function curl($finalAPIURL, $env, $institutionId)
    {
        $institutionId = empty($institutionId) ? 1 : $institutionId;//Client1 //101538 //102444
        $apiHeader = ($env == "PROD") ? array(
            'Content-Type: application/json'
        ) : array(
            'Content-Type: application/json',
            'Authorization: Basic bGVhcm5tdXNlcjpHZUBtN2RqITI='
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL =>$finalAPIURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS =>'{"InstitutionId":'.$institutionId.'}',
        CURLOPT_HTTPHEADER => $apiHeader,
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        
        Log::info('API to get details: ' .$finalAPIURL);
        Log::info('institutionId details: ' .$institutionId);
        return $response;

    }

    /**
     *  LTI OIDC login as per IMSGlobal.
     */
    public function oidc(Request $request)
    {
        $login = LTI_OIDC_Login::new(new LTIDBController());
        try {
            $redirect = $login->do_oidc_login_redirect(route('launch') . '?platform_client_id=' . $request->client_id);
            $redirect->do_redirect();
        } catch (LTI\OIDC_Exception $e) {
            echo 'Error doing OIDC login';
        }
    }

    /**
     *  JWK
     */
    public function jwk(Request $request)
    {
        $kid = '';
        $keys = array();
        $platformClientId = $request->client_id ?? '';
        $getKey =  Platform::select('issuer', 'platform_client_id', 'private_key')
            ->when($platformClientId, function ($query, $platformClientId) {
                return $query->where('platform_client_id', $platformClientId);
            })->get();

        foreach ($getKey as $keyValue) {
            $kid = hash('sha256', trim($keyValue['issuer'] . $keyValue['platform_client_id']));
            $keys[$kid] = $keyValue['private_key'];
        }
        $jwkKeys = JWKS_Endpoint::new($keys);
        $jwkKeys->output_jwks();
    }


    public function postResponseData(Request $request)
    {
        try {
            $requestId = $request->request_id;
            $selectedAssets = $request->selectedAssets;
            $launch = LTI_Message_Launch::from_cache($requestId, new LTIDBController());
            if (!$launch->is_deep_link_launch()) {
                $msg = "Must be a deep link!";
                throw new CustomException($msg);
            }
            $dl = $launch->get_deep_link();
            $resourceArr = array();
            foreach ($selectedAssets as $selectedAsset) {
                $resource = LTI_Deep_Link_Resource::new();
                $resource->set_title($selectedAsset['assetTitle']);
                $resource->set_type('ltiResourceLink');
                $resourceId = urlencode($selectedAsset['assetURL']);
                $resource->set_custom_params(array('resourceid' => $resourceId));
                $resource->set_url(route('launch') . "?resourceid=" . $resourceId);
                $resourceArr[] = $resource;
            }
            $dl->output_response_form($resourceArr);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
