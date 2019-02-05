<?php

namespace App\Modules\Admin\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Modules\Admin\Services\RedirectService;

class FormComposer
{

    /**
     * @var RedirectService
     */
    protected $redirectService;

    public function __construct(RedirectService $redirectService)
    {
        $this->redirectService = $redirectService;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('saveButtons', $this->redirectService->getSaveButtons());
    }
}