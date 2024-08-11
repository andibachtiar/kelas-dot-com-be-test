<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseUser extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    protected $table = "course_user";

    protected $guarded = ["id", "created_at", "udpated_at"];
}
