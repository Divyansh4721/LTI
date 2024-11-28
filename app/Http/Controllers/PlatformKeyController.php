<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlatformKey;
use Illuminate\Support\Facades\Auth;
use App\Models\Platform;
use Carbon\Carbon;

class PlatformKeyController extends Controller
{
    private $platform_per_page;
     
    public function __construct()
    {
        $this->platform_per_page = config('constant.platform_per_page');
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
            'platform_id' => 'required',
            'name' => 'required',
            'deployment_id' => 'required'
        ]);
        try {
            $model = new PlatformKey();
            $model->platform_id = $request->platform_id;
            $model->name = $request->name;
            $model->deployment_id = $request->deployment_id;
            $model->created_by= Auth::user()->id;
            $model->save();
            return redirect('create_lti_key/'.$request->platform_id)->with('success', 'Platfrom Key added successfully');
        } catch (\Exception $e) {
            exit($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        // Fetch the platform key using the validated ID
        $platformKey = PlatformKey::findOrFail($id);
        return view('platforms.edit_lti_platform_key', compact('platformKey'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Platform  $platform
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'deployment_id' => 'required'
            ]);
        try {
            PlatformKey::updateOrCreate(
                ['id' => $id],
                [
                    'name' => $request->name,
                    'deployment_id' => $request->deployment_id,
                    'platform_id' => $request->platform_id,
                    'created_by' => $request->created_by ?? Auth::user()->id,
                ],
            );
            return redirect('create_lti_key/'.$request->platform_id)
                ->with('success', 'Platfrom Key updated successfully');
        } catch (\Exception $e) {
            exit($e);
        }
    }

    public function platformKeys(Request $request, $id) {
        
        $platformKey = PlatformKey::where('platform_id', $id)->paginate($this->platform_per_page);
        $platformKey->setPath('/create_lti_key/'.$id);
        $platform = Platform::where('platform_id', $id)->whereNull('deleted_at')->get();

        return view('platforms.lti_platform_keys', compact('platformKey', 'platform', 'id'));
    }
    
    public function createPlatformKeys(Request $request, $id) {
        
        $platformKey = PlatformKey::where('platform_id', $id)->get();
        return view('platforms.create_lti_platform_keys', compact('platformKey', 'id'));
    }

    public function delete(Request $request, $id) {
        PlatformKey::where('id', $id)
                    ->update(['deleted_at' => Carbon::now()]);
        return redirect()->back()->with('success', 'Successfully Deleted The Platform Key.');
    }

}
