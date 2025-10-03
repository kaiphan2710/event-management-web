<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Base controller class that provides authorization and validation functionality
 * for all other controllers in the application
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
