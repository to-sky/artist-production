<?php

namespace App\Modules\Admin\Controllers;


use App\Modules\Admin\Requests\SettingsMailRequest;
use Illuminate\Http\Request;

class SettingController extends AdminController
{
    /**
     * Shows mail settings form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mail()
    {
        return view('Admin::setting.mail');
    }

    /**
     * Stores mail settings
     *
     * @param SettingsMailRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function mailStore(SettingsMailRequest $request)
    {
        $settings = $request->input('settings');

        foreach ($settings as $setting => $value) {
            setting([$setting => $value]);
        }

        setting()->save();

        return redirect()->route(config('admin.route') . '.settings.mail');
    }
}