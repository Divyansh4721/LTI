<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Exceptions\CustomException;
use DB;
use Illuminate\Support\Str;


class BooksController1 extends Controller
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

        if(!empty($productFiltersData['SiteDetails'])){
            $productFilters =  $productFiltersData['SiteDetails'];
            \Session::put('productFilters', $productFilters);
        }else{
            $productFilters = (!empty(\Session::get('productFilters'))) ? \Session::get('productFilters') : [];
        }
        //$productFilters = (!empty($productFiltersData['SiteDetails'])) ? $productFiltersData['SiteDetails'] : [];

        \Session::put('logo', 'images/default_mgh_logo.png');
        usort(
            $productFilters, fn(array $sort1, array $sort2): int => $sort1['SiteName'] <=> $sort2['SiteName']
        );
        \Session::put('instituteId', 102444);
        \Session::put('logo', 'images/default_mgh_logo.png');
        $logo = \Session::get('logo');


            
        return view('launch', compact('requestId', 'contentTypes', 'productFilters', 'logo'));
    }

    public function testJson(Request $request)
    {
        $dataSet = '{"TotalCount":"24","SearchResults":[{"ChapterId":"null","ContentType":"Textbooks","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"null","BookId":"1"},{"ChapterId":"null","ContentType":"Libros","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"null","BookId":"2"},{"ChapterId":"null","ContentType":"Livros","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"null","BookId":"3"},{"ChapterId":"null","ContentType":"Textbooks","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"null","BookId":"null"},{"ChapterId":"null","ContentType":"Libros","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"null","BookId":"null"},{"ChapterId":"null","ContentType":"Livros","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"null","BookId":"null"},{"ChapterId":"1","ContentType":"Book Chapter","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Chapter","BookId":"1"},{"ChapterId":"null","ContentType":"Book Chapter","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Chapter","BookId":"1"},{"ChapterId":"1","ContentType":"Book Chapter","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Section","BookId":"1"},{"ChapterId":"null","ContentType":"Book Chapter","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Section","BookId":"1"},{"ChapterId":"1","ContentType":"Book Chapter","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Section","BookId":"null"},{"ChapterId":"null","ContentType":"Book Chapter","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Section","BookId":"null"},{"ChapterId":"1","ContentType":"Cap\u00edtulo","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Chapter","BookId":"1"},{"ChapterId":"null","ContentType":"Cap\u00edtulo","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Chapter","BookId":"1"},{"ChapterId":"1","ContentType":"Cap\u00edtulo","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Section","BookId":"1"},{"ChapterId":"null","ContentType":"Cap\u00edtulo","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Section","BookId":"1"},{"ChapterId":"1","ContentType":"Cap\u00edtulo","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Section","BookId":"null"},{"ChapterId":"null","ContentType":"Cap\u00edtulo","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Section","BookId":"null"},{"ChapterId":"1","ContentType":"Quick Reference Resources","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Chapter","BookId":"1"},{"ChapterId":"1","ContentType":"Quick Reference Resources","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Section","BookId":"1"},{"ChapterId":"null","ContentType":"Quick Reference Resources","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Chapter","BookId":"1"},{"ChapterId":"null","ContentType":"Quick Reference Resources","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Section","BookId":"1"},{"ChapterId":"null","ContentType":"Quick Reference Resources","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Chapter","BookId":"null"},{"ChapterId":"null","ContentType":"Quick Reference Resources","Title":"Stroke","WebUrls":"null","CoverImageUrl":"null","Source":"null","ContentSnippet":"null","Duration":"null","SectionType":"Section","BookId":"null"}]}';

        $dataSet = json_decode($dataSet);

        dd($dataSet);
    }

    function click($ContentType, $SectionType, $ChapterID, $BookID){
        $enableClick = false;

        
    }
}