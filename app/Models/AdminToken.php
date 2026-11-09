<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Admin;

class AdminToken extends Model
{
    protected $table = 'admin_tokens';

    protected $fillable = [
        'admin_id',
        'token_admin',
        'valido_ate'
    ];
    public function usuario()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}