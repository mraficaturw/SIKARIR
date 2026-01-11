<?php

namespace App\Livewire;

use App\Models\Internjob;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ApplyButton extends Component
{
    public int $jobId;
    public bool $isApplied = false;
    public ?string $applyUrl = null;

    public function mount(int $jobId, ?string $applyUrl = null)
    {
        $this->jobId = $jobId;
        $this->applyUrl = $applyUrl;

        /** @var UserAccount|null $user */
        $user = Auth::guard('user_accounts')->user();

        if ($user && $user->hasVerifiedEmail()) {
            $this->isApplied = $user->appliedJobs()->where('internjob_id', $this->jobId)->exists();
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

        if ($user->appliedJobs()->where('internjob_id', $this->jobId)->exists()) {
            $user->appliedJobs()->detach($this->jobId);
            $this->isApplied = false;
        } else {
            $user->appliedJobs()->attach($this->jobId, ['applied_at' => now()]);
            $this->isApplied = true;
        }
    }

    public function render()
    {
        return view('livewire.apply-button');
    }
}
