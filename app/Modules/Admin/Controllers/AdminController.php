<?php

namespace App\Modules\Admin\Controllers;


use App\Modules\Admin\Services\RedirectService;
use App\Modules\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Controllers\Traits\SaveButtons;
use App\Models\Menu;

class AdminController extends Controller
{

    /**
     * @var RedirectService
     */
    protected $redirectService;


    /**
     * Create a new controller instance.
     *
     */
    public function __construct(RedirectService $redirectService)
    {
        $this->redirectService = $redirectService;
    }

}