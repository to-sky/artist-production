<?php

namespace App\Modules\Admin\Controllers;


use App\Modules\Admin\Services\RedirectService;
use App\Modules\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Models\Menu;
use Prologue\Alerts\Facades\Alert;

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

    public function setLocale($locale, Request $request)
    {
        $languages = array_keys(config('admin.languages'));
        
        if (in_array($locale, $languages)) {
            App::setLocale($locale);

            Cookie::queue('locale', $locale);
        } else {
            Alert::warning(__('Locale :locale not supported.', ['locale' => $locale]))->flash();
        }

        return redirect()->back();
    }

}