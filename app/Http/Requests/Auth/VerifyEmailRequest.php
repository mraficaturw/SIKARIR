<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\UserAccount;

class VerifyEmailRequest extends EmailVerificationRequest
{
    /**
     * Get the guard to be used during authentication.
     *
     * @return string|null
     */
    protected function guard()
    {
        return 'user_accounts';
    }

    /**
     * Get the user for the request.
     *
     * @return mixed
     */
    public function user($guard = null)
    {
        if (property_exists($this, 'user') && $this->user) {
            return $this->user;
        }

        $guard = $guard ?: $this->guard();

        $id = $this->route('id');

        if (is_null($id)) {
            return null;
        }

        return UserAccount::find($id);
    }
}
