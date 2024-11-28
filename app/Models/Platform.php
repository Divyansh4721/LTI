<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Platform extends Model
{
    use HasFactory,Notifiable;

    protected $table = 'lti_platforms';

    protected $primaryKey = 'platform_id';

    protected $fillable = [
        'name',
        'issuer',
        'platform_client_id',
        'private_key',
        'public_key',
        'lti_version',
        'signature_method',
        'platform_name',
        'platform_version',
        'platform_guid',
        'mgh_client_id',
        'profile',
        'tool_proxy',
        'jwkseturl',
        'access_token',
        'authorization_url',
        'logo',
        'enabled',
        'enable_from',
        'enable_until',
        'deleted_at',
        'protected',
        'created_by'
    ];

    public $timestamps = true;

    /**
     * Get the client associated with the platform.
     */
    public function client()
    {
        return $this->hasMany(Client::class, 'client_id', 'mgh_client_id');
    }
    public function deployment()
    {
        return $this->hasMany(PlatformKey::class, 'platform_id', 'platform_id');
    }

    public function platformData($platformClientId)
    {
       return Platform::select('mgh_client_id')
                    ->where('platform_client_id', $platformClientId)->first();

    }
}
