<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Exceptions\CustomException;
use DB;
use Illuminate\Support\Str;


class BooksControllerTest extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $requestId = '';
        
        $contentTypes = (new LaunchController)->getContentTypes();

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
        $url = \Config::get('constant.search_API.domain');
        $env = \Config::get('app.env');
        $path = config('constant.search_API.path');
        $apiKey = config('constant.search_API.apiKey');
        $productFiltersData = (new LaunchController)->GetProductFilterList($url, $env, $path, $apiKey);
        $productFilters = (!empty($productFiltersData['SiteDetails'])) ? $productFiltersData['SiteDetails'] : [];

        \Session::put('logo', 'images/default_mgh_logo.png');
        usort(
            $productFilters, fn(array $sort1, array $sort2): int => $sort1['SiteName'] <=> $sort2['SiteName']
        );
        \Session::put('instituteId', 102444);
        \Session::put('logo', 'images/default_mgh_logo.png');
        $logo = \Session::get('logo');

        // return view('launch', compact('requestId', 'contentTypes', 'productFilters', 'logo'));

        // Set Content Security Policy
        $csp = url('/');
        return response()
        ->view('launch', compact('requestId', 'contentTypes', 'productFilters', 'logo'), 200)
        ->header('Content-Security-Policy', "frame-ancestors 'self' {$csp};", true);
    }
}