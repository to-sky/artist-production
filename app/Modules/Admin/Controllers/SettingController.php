<?php

namespace App\Modules\Admin\Controllers;


use App\Modules\Admin\Requests\SettingsMailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Setting;

class SettingController extends AdminController
{
    /**
     * Shows mail settings form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mail()
    {
        Setting::setExtraColumns(array(
            'user_id' => Auth::user()->id
        ));

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

        Setting::setExtraColumns(array(
            'user_id' => Auth::user()->id
        ));

        foreach ($settings as $setting => $value) {
            setting([$setting => $value]);
        }

        setting()->save();

        return redirect()->route(config('admin.route') . '.settings.mail');
    }
}