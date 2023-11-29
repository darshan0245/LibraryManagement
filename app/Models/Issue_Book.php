<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue_Book extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_id', 'user_name', 'book_name', 'issue_date', 'due_date'];

    protected $table = 'issue_books';


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function book(){
        return $this->belongsTo(Book::class);
    }
}

