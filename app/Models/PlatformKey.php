<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformKey extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'lti_platform_keys';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'platform_id',
        'deployment_id',
        'created_by',
    ];

    public $timestamps = true;

     /**
     * Get the client associated with the platform.
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class, 'platform_id', 'platform_id');
    }
}
