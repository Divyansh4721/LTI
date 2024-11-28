<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use App\Models\ESCRUD;

class ClientAPIController extends Controller
{
    private $deleteError;private $notAvailable;private $successStatus;private $serverError;
    private $notExist;private $insertError;private $deleteSuccess;private $updateSuccess;private $updateError;
    private $insertSuccess;private $fetchSuccess;private $fetchError;
    private $count;private $maxEntry;private $error;
    
    public function __construct()
    {
        $this->deleteError = config('constant.delete.error');$this->serverError = config('constant.server_error');
        $this->successStatus = config('constant.success');$this->notAvailable = config('constant.not_available');
        $this->notExist = config('constant.not_exist');$this->insertError = config('constant.insert.error');
        $this->deleteSuccess = config('constant.delete.success');$this->fetchSuccess = config('constant.fetch.success');
        $this->updateSuccess = config('constant.update.success');$this->updateError = config('constant.update.error');
        $this->insertSuccess = config('constant.insert.success');$this->fetchError = config('constant.fetch.error');
        $this->restoreSuccess = config('constant.restore.success');
        $this->restoreError = config('constant.restore.error');
        $this->count = 0;
        $this->maxEntry = 'Maximum 1000 records to allowd at a time';
        $this->error = config('constant.error');
    }

    /**
     * @OA\Get(
     * path="/api/client-list",
     * operationId="clientList",
     * tags={"Clients"},
     * security={{"bearerAuth": {} },},
     * summary="Show Client List",
     * description="Show Client List",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(),
     *        ),
     *    ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Data Fetched Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Data Fetched Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index()
    {
        $isClientExist = Client::get();

        if (count($isClientExist) == 0) {
            return response()->json([$this->notExist], $this->notAvailable);
        }

        try {
            return response()->json([
                'Success' =>  $this->notExist,
                'clientData' => $isClientExist
            ], $this->successStatus);
        } catch (\Exception $e) {
            return response()->json([
                'Error' => $this->fetchError,
                'errorMessage' => $e->getMessage()
            ], $this->serverError);
        }
    }

    /**
     * @OA\Post(
     * path="/api/add-client",
     * operationId="addClient",
     * tags={"Clients"},
     * summary="Add Client",
     * description="Add Client Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name", "description"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="description", type="text")
     *            ),
     *        ),
     *    ),
     *      @OA\Examples(
     *        summary="VehicleStoreEx1",
     *        example = "VehicleStoreEx1",
     *        value = {
     *           "name": "vehicle 1"
     *         },
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Inserted Client Records Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Inserted Client Records Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    public function store(Request $request)
    {
        $clientData = $request->all();
        $clientModel = new Client();

        if (count($clientData) <= 1000) {
        try {
            return $clientModel->clientApiData(
                $clientData,
                $this->count,
                $this->insertSuccess,
                $this->successStatus
            );
            
            } catch (Exception $e) {
                return response()
                    ->json(
                        [
                            'Error' => $this->insertError, 'errorMessage' => $e->getMessage(),
                        ],
                        $this->serverError
                    );
            }
        } else {
            return response()
                ->json(
                    [
                        'Error' => $this->maxEntry, 'errorMessage' => $e->getMessage(),
                    ],
                    $this->error
                );
        }
    }

    /**
     * @OA\Get(
     * path="/api/client/{id}",
     * operationId="showClient",
     * tags={"Clients"},
     * security={{"bearer_token": {} }},
     * summary="Show Client",
     * description="Show Client Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(),
     *        ),
     *    ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Data fetched Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Data fetched Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show($id)
    {
        $isClientExist = Client::select('client_id', 'name', 'description')
        ->where('client_id', $id)->get();
        
        if (count($isClientExist) == 0) {
            return response()->json(['errorMessage' => $this->notExist], $this->notAvailable);
        }

        try {
            return response()
            ->json(['Success' => config('constantMessages.fetch.success'),
            'clientData' => $isClientExist], $this->successStatus);
        } catch (\Exception $e){
            return response()
                ->json(['Error' => $this->fetchError, 'errorMessage' => $e->getMessage()], $this->serverError);
        }
    }

    /**
     * @OA\Patch(
     * path="/api/edit-client/{id}",
     * operationId="editClient",
     * tags={"Clients"},
     * summary="Edit Client",
     * description="Edit Client Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name", "description"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="description", type="text")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Edited Client Records Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Edited Client Records Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update(Request $request, $id)
    {
        $isClientTrashed = Client::where('client_id', $id)->onlyTrashed()->get();
        if (count($isClientTrashed) > 0) {
            $clientData = Client::onlyTrashed()->findOrFail($id);
            $clientData->restore();
        }

        $isClientExist = Client::where('client_id', $id)->get();

        if (count($isClientExist) == 0) {
            return response()->json(['errorMessage' => $this->notExist], $this->notAvailable);
        }

        try {
            Client::updateOrCreate(
                ['client_id' => $id],
                [
                    'client_id' => $id,
                    'name' => $request->name,
                    'description' => $request->description
                ]
            );
            
            $clientData = Client::select('client_id', 'name', 'description')->where('client_id', $id)->get();
            return response()
                ->json(['Success' => $this->updateSuccess, 'clientData' => $clientData], $this->successStatus);
        } catch (\Exception $e) {
            return response()
                ->json(['Error' => $this->updateError, 'errorMessage' => $e->getMessage()], $this->serverError);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/delete-client/{id}",
     * operationId="deleteClient",
     * tags={"Clients"},
     * summary="Delete Client",
     * description="Delete Client Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name", "description"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="description", type="text")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Client Records Deleted Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Client Records Deleted Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy($id)
    {
        try {
            $clientId = explode(',', $id);
            $trashedCount = 0;
            $isClientTrashed = Client::whereIn('client_id', $clientId)->onlyTrashed()->get();
            $isClientExist = Client::whereIn('client_id', $clientId)->get();

            $trashedID = [];
            foreach ($isClientTrashed as $trashed) {
                $trashedID []= $trashed['client_id'];
                $trashedCount = $this->count +=1;
            }
        
            $existID = [];
            foreach ($isClientExist as $exist) {
                $existID []= $exist['client_id'];

                Client::where('client_id', $exist['client_id'])
                    ->update(['deleted_at' => Carbon::now()]);
    
                    $this->count +=1;
            }
            
            $trashedAndExist = array_merge($existID, $trashedID);

            $clientNotAvailable = array_values(array_diff($clientId, $trashedAndExist));
            return response()
                        ->json(
                            [
                                'Success' => $this->deleteSuccess,
                                'total_count' => count($clientId),
                                'total_deleted' => count($clientId)-$trashedCount-count($clientNotAvailable),
                                'total_failed' => $trashedCount,
                                'total_not_found' => count($clientNotAvailable),
                                'failed_client' => [
                                    'client_id' => $trashedID,
                                    'errorMessage' => "These client are inactivated or deleted"
                                ],
                                'not_found_client' => [
                                    'client_id' => $clientNotAvailable,
                                    'errorMessage' => "These client id's record not found"
                                ]
                            ],
                            $this->successStatus
                        );

                } catch (Exception $e) {
                    return response()->json([
                        'Error' => $this->deleteError,
                        'errorMessage' => $e->getMessage(),
                    ], $this->serverError);
                }
    }

    /**
     * @OA\Get(
     * path="/api/trashed-client-list",
     * operationId="trashedClientList",
     * tags={"Clients"},
     * security={{"bearerAuth": {} },},
     * summary="Show Trashed Client List",
     * description="Show Trashed Client Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(),
     *        ),
     *    ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Data Fetched Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Data Fetched Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function trash(Request $request)
    {
        $isTrashedClientExist = Client::onlyTrashed()->get();

        if (count($isTrashedClientExist) == 0) {
            return response()->json([$this->notExist], $this->notAvailable);
        }

        try {
            $trashedClientData = Client::onlyTrashed()->get();
            return response()
                ->json(['Success' => $this->fetchSuccess,
                'trashedClientData' => $trashedClientData], $this->successStatus);
        } catch (\Exception $e) {
            return response()
                ->json(['Error' => $this->fetchError, 'errorMessage' => $e->getMessage()], $this->serverError);
        }
    }

    /**
     * @OA\Get(
     * path="/api/restore-client/{id}",
     * operationId="restoreClientList",
     * tags={"Clients"},
     * security={{"bearerAuth": {} },},
     * summary="Restore Trashed Client List",
     * description="Restore Trashed Client Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(),
     *        ),
     *    ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Data Fetched Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Data Fetched Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function restore(Request $request, $id)
    {
        $isTrashedClientExist = Client::onlyTrashed()->where('client_id', $id)->get();

        if (count($isTrashedClientExist) == 0) {
            return response()->json([$this->notExist], $this->notAvailable);
        }

        try {
            $clientData = Client::onlyTrashed()->findOrFail($id);
            $clientData->restore();
            return response()->json(['Success' => config('constantMessages.restore.success')], 200);
        } catch (Exception $e) {
            return response()->json(['Error' => config('constantMessages.restore.error'),
            'errorMessage' => $e->getMessage()], 500);
        }
    }
}
