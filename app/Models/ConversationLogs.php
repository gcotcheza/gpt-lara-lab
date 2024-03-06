<?php

namespace App\Models;

use App\Models\ConversationFileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConversationLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_message',
        'bot_response'
    ];

    /**
     * This convesation belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fileUpload()
    {
        return $this->hasOne(ConversationFileUpload::class, 'id', 'file_upload_id');
    }
}
