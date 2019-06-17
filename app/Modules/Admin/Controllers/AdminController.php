<?php

namespace App\Modules\Admin\Controllers;


use App\Modules\Admin\Services\RedirectService;
use App\Modules\Controller;
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

    public function setLocale($locale)
    {
        $languages = array_keys(config('admin.languages'));

        if (! in_array($locale, $languages)) {
            Alert::warning(__('Locale :locale not supported.', ['locale' => $locale]))->flash();

            return redirect()->back();
        }

        app()->setLocale($locale);

        return redirect()->back()->cookie('locale', $locale);
    }
}
