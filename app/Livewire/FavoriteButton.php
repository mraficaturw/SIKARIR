<?php

namespace App\Livewire;

use App\Models\Internjob;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FavoriteButton extends Component
{
    public int $jobId;
    public bool $isFavorite = false;

    public function mount(int $jobId)
    {
        $this->jobId = $jobId;

        /** @var UserAccount|null $user */
        $user = Auth::guard('user_accounts')->user();

        if ($user && $user->hasVerifiedEmail()) {
            $this->isFavorite = $user->favorites()->where('internjob_id', $this->jobId)->exists();
        }
    }

    public function toggle()
    {
        /** @var UserAccount|null $user */
        $user = Auth::guard('user_accounts')->user();

        if (!$user) {
            return $this->redirect(route('login'));
        }

        if (!$user->hasVerifiedEmail()) {
            return $this->redirect(route('verification.notice'));
        }

        $job = Internjob::findOrFail($this->jobId);

        if ($user->favorites()->where('internjob_id', $this->jobId)->exists()) {
            $user->favorites()->detach($this->jobId);
            $this->isFavorite = false;
        } else {
            $user->favorites()->attach($this->jobId);
            $this->isFavorite = true;
        }
    }

    public function render()
    {
        return view('livewire.favorite-button');
    }
}
