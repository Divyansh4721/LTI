<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceLog extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    protected $table = 'lti_resource_log';

    protected $fillable = [
        'resource_link_id',
        'resource_link_title',
        'message_type',
        'lti_resource_link',
        'resource_id',
        'roles',
        'launch_presentation',
        'tool_platform',
        'account_name',
        'context_title',
        'user_id',
        'name',
        'email',
        'issuer',
        'client_id',
        'deployment_id',
        'nonce',
        'sub',
        'lti_version',
        'placement',
        'exp',
        'headers',
        'payload',
        'referred',
        'settings'
    ];

    public $timestamps = true;
}
