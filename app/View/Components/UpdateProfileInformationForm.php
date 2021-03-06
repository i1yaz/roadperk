<?php

namespace App\View\Components;

use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Country;
use App\Models\VehicleType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    /**
     * The new avatar for the user.
     *
     * @var mixed
     */
    public $photo;

    /**
     * Determine if the verification email was sent.
     *
     * @var bool
     */
    public $verificationLinkSent = false;

    /**
     * get list of all countries
     *
     * @var array
     */
    public $countries;

    /**
     * country Id of user
     *
     * @var array
     */
    public $country_id;

    /**
     * vehicles
     *
     * @var VehicleType
     */
    public $vehicleTypes;

    /**
     * vehicles Id of user
     *
     * @var array
     */
    public $userVehicles = [];

    /**
     * newsletter
     *
     * @var bool
     */
    public $newsletter = false;

    /**
     * newsletter
     *
     * @var bool
     */
    public $upcomingEventNotifications = false;

    /**
     * Prepare the component.
     *
     * @return void
     */
    public function mount()
    {
        $user = Auth::user();
        $newsletters = json_decode($user['newsletters'], true);
        $this->state = $user->withoutRelations()->toArray();
        $this->country_id = $this->state['country_id'];
        $this->userVehicles = $user->vehicleTypes->pluck('id')->toArray();
        if (! empty($newsletters) && is_array($newsletters)) {
            $this->newsletter = $newsletters['newsletter'];
            $this->upcomingEventNotifications = $newsletters['upcomingEventNotifications'];
        }
        $this->vehicleTypes = Cache::rememberForever('vehicleTypes', function () {
            return VehicleType::get();
        });
        $this->countries = Cache::rememberForever('countries', function () {
            return Country::get();
        });
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Laravel\Fortify\Contracts\UpdatesUserProfileInformation  $updater
     * @return void
     */
    public function updateProfileInformation(UpdateUserProfileInformation $updater)
    {
        $this->constructState();
        $this->resetErrorBag();
        $updater->update(
            Auth::user(),
            $this->photo
                ? array_merge($this->state, ['photo' => $this->photo])
                : $this->state
        );

        if (isset($this->photo)) {
            return redirect()->route('profile.show');
        }

        $this->emit('saved');

        $this->emit('refresh-navigation-menu');
    }

    public function constructState()
    {
        $this->state = array_merge($this->state, ['country_id' => $this->country_id]);
        $this->state = array_merge($this->state, ['userVehicles' => $this->userVehicles]);
        $notifications = [
            'notifications' => [
                'newsletter' => $this->newsletter,
                'upcomingEventNotifications' => $this->upcomingEventNotifications,
            ],
        ];
        $this->state = array_merge($this->state, $notifications);
    }

    /**
     * Delete user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        Auth::user()->deleteProfilePhoto();

        $this->emit('refresh-navigation-menu');
    }

    /**
     * Sent the email verification.
     *
     * @return void
     */
    public function sendEmailVerification()
    {
        Auth::user()->sendEmailVerificationNotification();

        $this->verificationLinkSent = true;
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('profile.update-profile-information-form');
    }
}
