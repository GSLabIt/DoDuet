<?php

namespace App\Http\Controllers;

use Ebalo\EasyCRUD\EasyCRUD;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use EasyCRUD;
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
