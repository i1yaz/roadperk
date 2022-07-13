<?php

namespace App\Http\Livewire\Profile;

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class GoogleAnalyticsFeature extends Component
{
    public $googleAnalyticsCode;

    public function mount()
    {
        $generalSetting = Cache::rememberForever('generalSettings', function () {
            return GeneralSetting::get();
        });
        $setting = $generalSetting->where('key', 'google_analytics')->first();
        $this->googleAnalyticsCode = $setting ? $setting->value : '';
    }

    public function submit()
    {
        GeneralSetting::updateOrCreate(
            ['key' => 'google_analytics'],
            ['key' => 'google_analytics', 'value' => $this->googleAnalyticsCode]
        );
    }

    public function render()
    {
        return view('profile.google-analytics-feature');
    }
}
