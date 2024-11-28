<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Product;
use App\Models\Platform;
use App\Models\Client;
use App\Exceptions\CustomException;
use DB;
use Elasticsearch\ClientBuilder;
use App\Models\ESCRUD;

class BooksController extends Controller
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
        $platformModel = new Platform();
        $productModel = new Product();
       
        $platformClientId = $request->platform_client_id ?? '';
        $requestId = '';
        $platformData = $platformModel->platformData($platformClientId);
        $clientId = $platformData['mgh_client_id'] ?? '';
        $assetProduct = $productModel->assetProduct($clientId);
        $productIds = [];
        foreach ($assetProduct as $productId) {
            $productIds []= $productId['product_id'];
        }
        $assetProductID = $productIds ?? [];
                $search = $request->search ?? '';
                $product = $request->product ?? '';
                $sort = $request->sort ?? '';
                $parentId = $request->parent_id ?? '';
                $requestId = $request->requestId ?? '';
                $bc1 = $request->bc1 ?? null;
                $bc2 = $request->bc2 ?? null;
                $bc3 = $request->bc3 ?? null;
                $bc4 = $request->bc4 ?? null;
                $bc5 = $request->bc5 ?? null;
                $childList = array($bc1, $bc2, $bc3, $bc4, $bc5);
                $linkNotAvailable = $this->notAvailableLink;
                $pointerEventsStyle = $this->pointerEvents;

                $parentChild = Asset::select('asset_id', 'title', 'parent_id')->whereIn('asset_id', $childList)->get();
                
                $assetResultData = $this->getAssetData(
                    $requestId,
                    $linkNotAvailable,
                    $pointerEventsStyle,
                    $parentId,
                    $product,
                    $assetProductID,
                    $parentChild,
                    $search,
                    $sort,
                    $platformClientId,
                    $bc1,
                    $bc2,
                    $bc3,
                    $bc4,
                    $bc5,
                    $assetProduct
                );

                $assetData = $assetResultData['assetData'];
                $params = $assetResultData['params'];
                $parentIDs = $assetResultData['parentIDs'];
                $assetCount = $assetResultData['assetCount'];
                $elasticProductID = $assetResultData['elasticProductID'];


                return view('books', compact('assetData','requestId', 'params', 'assetProduct', 'parentId', 'platformClientId', 'product', 'linkNotAvailable', 'parentIDs', 'pointerEventsStyle', 'assetCount', 'parentChild', 'bc1', 'bc2', 'bc3', 'bc4', 'bc5', 'elasticProductID'));
    }




    /**
     * To get all asset related data
     */
    public function getAssetData($requestId, $linkNotAvailable, $pointerEventsStyle, $parentId, $product, $assetProductID, $parentChild, $search, $sort, $platformClientId, $bc1, $bc2, $bc3, $bc4, $bc5, $assetProduct)
    {
           $assetModel = new Asset();
           $parentIDs = [];
           $allAssetData = $assetModel->allAssetData($parentId, $product, $assetProductID);
           foreach ($allAssetData as $assets) {
               $parentIDs[] = $assets['parent_id'];
           }
        //    $parentChild = Asset::select('asset_id', 'title', 'parent_id')->whereIn('asset_id', $childList)->get();
           if ($product != null) {
               $elasticProductID = array($product);
           } else {
               $elasticProductID = $assetProductID;
           }
           $assetData = $assetModel
               ->assetData($parentId, $this->assetPerPage, $search, $elasticProductID, $sort, $requestId, $product, $assetProductID);
           $assetData->setPath(config('app.url').'ltilaunch');
           $assetCount = $assetModel->assetCount($parentId, $search, $assetProductID, $product);
           $params = array(
               'parent_id' => $parentId, 'requestId' => $requestId,
               'search' => $search, 'product' => $product, 'sort' => $sort,
               'platform_client_id' => $platformClientId,
               'parentChild' => $parentChild,
               'bc1' => $bc1,
               'bc2' => $bc2,
               'bc3' => $bc3,
               'bc4' => $bc4,
               'bc5' => $bc5
           );
           return ['assetData' => $assetData, 'params' => $params,  'parentIDs' => $parentIDs,  'assetCount' => $assetCount, 'parentChild' => $parentChild, 'elasticProductID' => $elasticProductID];
        }


   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}



