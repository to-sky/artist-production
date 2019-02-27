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

    /**
     * Returns datatables jquery plugin translation json
     *
     * @param $locale
     * @return mixed
     */
    public function dataTablesLocale($locale)
    {
        $path = base_path('app/Modules/Admin/resources/lang/dataTables/' . $locale . '.json');

        if (is_file($path)) {
            return json_decode(file_get_contents($path), true);
        }
    }

}