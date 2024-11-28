<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'description',
        'deleted_at'
    ];

    protected $primaryKey = 'client_id';

    public $incrementing = false;
    
    public $timestamps = true;

    public function platforms()
    {
        return $this->belongsTo(Platform::class, 'mgh_client_id', 'client_id');
    }

    // public function products()
    // {
    //     return $this->belongsToMany(Product::class, 'product_client', 'client_id');
    // }

    public function clientApiData($clientData, $count, $insertSuccess, $successStatus)
    {
        $failedCount = 0;
        $errorMessage = '';
        $inactivatedClient = [];
        foreach ($clientData as $client) {
            
            $isClientExist = Client::where('client_id', $client['client_id'])->onlyTrashed()->get();
            if (count($isClientExist) > 0) {
                $clientData = Client::onlyTrashed()->findOrFail($client['client_id']);
                $clientData->restore();
            }
                $client = Client::updateOrCreate(
                    ['client_id' => $client['client_id']],
                    [
                    'name' => $client['name'],
                    'description' => $client['description'],
                    'client_id' => $client['client_id']
                    ]
                );

                $count += 1;
                $errorMessage = '';
                $failedCount = 0;
        }
        return response()
                ->json(
                    [
                        'Success' => $insertSuccess,
                        'total_count' => $count,
                        'total_inserted' => $count-$failedCount,
                        'total_failed' => $failedCount,
                        'failed_client' => [
                                            'client_id' => $inactivatedClient,
                                            'errorMessage' => $errorMessage
                                           ]
                    ],
                    $successStatus
                );
    }
    
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
