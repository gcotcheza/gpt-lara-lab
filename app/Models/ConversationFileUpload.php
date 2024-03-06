<?php

namespace App\Models;

use App\Models\ConversationLogs;
use App\Models\ConversationHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConversationFileUpload extends Model
{
    use HasFactory;

    public function conversationHistory()
    {
        return $this->belongsTo(ConversationLogs::class, 'converstion_file_upload_id');
    }
}
