<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * @var int
     */
    protected $limitPerPage = 10;

    /**
     * @var string
     */
    protected $sort = 'created_at';

    /**
     * @var string
     */
    protected $sortDirection = 'asc';
}
