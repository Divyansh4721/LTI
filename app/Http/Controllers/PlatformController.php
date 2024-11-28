<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use phpseclib\Crypt\RSA;
use Carbon\Carbon;

class PlatformController extends Controller
{

    private $platform_per_page;
     
    public function __construct()
    {
        $this->platform_per_page = config('constant.platform_per_page');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $platformData = Platform::with('client')->with('deployment')->whereNull('deleted_at')->orderBy('created_at', 'desc')->paginate($this->platform_per_page);
        $platformData->setPath('/dashboard');
        return view('platforms.lti_platform', compact('platformData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'mgh_client_id' => 'required',
            'platfrom_name' => 'required',
            'issuer' => 'required',
            'client_id' => 'required',
            'public_key' => 'required',
            'private_key' => 'required',
            'jwkseturl' => 'required',
            'access_token' => 'required',
            'authorization_url' => 'required',
            'logo' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:150000|dimensions:min_width=50,max_width=150,min_height=50,max_height=80'
        ]);
        try {
            $model = new Platform();
            $model->name = $request->platfrom_name;
            $model->issuer = $request->issuer;
            $model->platform_client_id = $request->client_id;
            $model->private_key = $request->private_key;
            $model->public_key = $request->public_key;
            $model->lti_version = $request->name;
            $model->signature_method = "RSA256";
            $model->platform_name = $request->name;
            $model->platform_version = $request->name;
            $model->platform_guid = $request->name;
            $model->mgh_client_id = $request->mgh_client_id;
            $model->profile = $request->name;
            $model->jwkseturl = $request->jwkseturl;
            $model->access_token = $request->access_token;
            $model->authorization_url = $request->authorization_url;
            $model->protected = 1;
            $model->enabled = ($request->enabled) ? 1 : 0;
            $model->created_by= Auth::user()->id;
            
            $logo = $request->file('logo');
            $path = "client-logo/".time().$logo->getClientOriginalName();
            Storage::disk("s3")->put($path, file_get_contents($logo));
            $model->logo = $path;

            $model->save();
            return redirect('dashboard')->with('success', 'Platfrom added successfully');
        } catch (\Exception $e) {
            exit($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function show(Platform $platform)
    {
        $appUrl = url('/');
        $crypt = new RSA();
        $keys = $crypt->createKey();
        $privateKey = isset($keys['privatekey']) ? $keys['privatekey'] : null;
        $publicKey = isset($keys['publickey']) ? $keys['publickey'] : null;
        $clientData = Client::orderBy('name', 'ASC')->get();
        return view('platforms.create_lti_platform', compact('clientData', 'privateKey', 'publicKey', 'appUrl'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function edit(Platform $platformData)
    {
        $appUrl = url('/');
        $clientData = Client::orderBy('name', 'ASC')->get();
        return view('platforms.edit_lti_platform', compact('platformData', 'clientData', 'appUrl'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Platform $platform)
    {
        $request->validate([
            'mgh_client_id' => 'required',
            'platfrom_name' => 'required',
            'issuer' => 'required',
            'client_id' => 'required',
            'public_key' => 'required',
            'private_key' => 'required',
            'jwkseturl' => 'required',
            'access_token' => 'required',
            'authorization_url' => 'required',
            'logo' => 'image|mimes:jpg,jpeg,png,gif,svg|max:150000|dimensions:min_width=50,max_width=150,min_height=50,max_height=80'
        ]);
        try {
            if(!empty($request->logo)) {
                $logoName = $request->file('logo');
                $path = "client-logo/".time().$logoName->getClientOriginalName();
                Storage::disk("s3")->put($path, file_get_contents($logoName));
                $logo = $path;
            } else {
                $logo = $request->old_logo;
            }
            
            Platform::updateOrCreate(
                ['platform_id' => $request->platform_id],
                [
                    'platform_id' => $request->platform_id,
                    'name' => $request->platfrom_name,
                    'issuer' => $request->issuer,
                    'platform_client_id' => $request->client_id,
                    'private_key' => $request->private_key,
                    'public_key' => $request->public_key,
                    'platform_name' => $request->platfrom_name,
                    'mgh_client_id' => $request->mgh_client_id,
                    'jwkseturl' => $request->jwkseturl,
                    'access_token' => $request->access_token,
                    'authorization_url' => $request->authorization_url,
                    'logo' => $logo,
                    'protected' => $request->protected ?? 1,
                    'enabled' => $request->enabled ?? 1,
                    'created_by' => $request->created_by ?? 1
                ],
            );
            return redirect()->route('platform.index')
                ->with('success', 'Platfrom updated successfully');
        } catch (\Exception $e) {
            exit($e);
        }
    }

    /**
     * Change the status to active or inactive.
     *
     * @param  \App\Models\Platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $platform = Platform::find($request->platform_id);
        $platform->enabled = $request->enabled;
        $platform->save();
        return response()->json(['success'=>'Status change successfully.'], 200);
    }


    public function delete(Request $request, $id) {
        Platform::where('platform_id', $id)
                    ->update(['deleted_at' => Carbon::now()]);
        return redirect()->back()->with('success', 'Successfully Deleted The Platform.');
    }

}
