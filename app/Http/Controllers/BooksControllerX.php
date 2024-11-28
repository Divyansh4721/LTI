<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Exceptions\CustomException;
use DB;


class BooksControllerX extends Controller
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
        $searchData = $request->search ?? '';
        $fl_TopLevelContentDisplayName = $request->fl_TopLevelContentDisplayName ?? '';
        $page = $request->page ?? 1;
        $response = $this->searchAndPagination($page, $searchData, $fl_TopLevelContentDisplayName);
        //print_r($response);
        //die;
        $responseArr = $response['SearchResults'] ?? '';
        $totalRecors = $response['TotalCount'] ?? 0;
        
        return view('books1', compact('responseArr', 'totalRecors', 'page'));
    }


    public function apiResponse(Request $request)
    {
        if($request->ajax()){
            $x = "AJAX";
        } else {
        $x = "HTTP";
        }
        
        $response = $this->searchAndPagination();
       
        $responseArr = $response['SearchResults'];
        $totalRecors = $response['TotalCount'];

        $table_view = view('api-responce', compact('responseArr', 'totalRecors'));
        // return response()->json(['succes' => $x, 'table_view' => $table_view]);
        return $table_view;

    }

    
    public function searchAndPagination($page, $searchData, $fl_TopLevelContentDisplayName)
    {
        $page = 'page='.$page;
        
        if ($searchData == '' || $searchData == null) {
            $q= 'q*';
        } else {
            $q='q='.$searchData;
        }

        if ($fl_TopLevelContentDisplayName == '' || $fl_TopLevelContentDisplayName == null) {
            $fl_TopLevelContentDisplayName='fl_TopLevelContentDisplayName=';
        } else {
            $fl_TopLevelContentDisplayName = 'fl_TopLevelContentDisplayName='.$fl_TopLevelContentDisplayName;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://mghrc.silverchair.com/lti/api/clientId/11/apiKey/C790E565-F8B6-453D-A5D2-CA7D7DB2800F/search?'.$q.'&'.$page.'&'.$fl_TopLevelContentDisplayName,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS =>'{"InstitutionId":101538}',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic bGVhcm5tdXNlcjpHZUBtN2RqITI='
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $responseArr = json_decode($response, true);

        return $responseArr;
    }


    }
