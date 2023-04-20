<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Http\Resources\StatusResource;

class StatusController extends Controller
{
    public function getStatus()
    {
        $status = Status::all();
        return StatusResource::collection($status);
    }
}
