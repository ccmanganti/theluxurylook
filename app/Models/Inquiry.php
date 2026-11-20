<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $table = 'inquiries';
    protected $fillable = ['name', 'email', 'phone', 'service', 'message', 'status'];
    protected function casts(): array { return []; }

}
