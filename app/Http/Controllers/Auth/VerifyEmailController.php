<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

/**
 * VerifyEmailController
 */
class VerifyEmailController extends Controller
{
    /**
     * verify
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function verify(Request $request): RedirectResponse
    {
        $user = User::findOrFail($request->route('id'));

        // Verifying hash integrity
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect($this->frontendUrl('/login?verified=invalid'));
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->frontendUrl('/login?verified=already'));
        }

        // Mark as verified and fire the event
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Custom redirect after verification
        return redirect($this->frontendUrl('/login?verified=1'));
    }

    /**
     * frontendUrl
     *
     * @param  mixed $path
     * @return string
     */
    private function frontendUrl(string $path): string
    {
        $base = rtrim(config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:8000')), '/');
        return $base . $path;
    }
}
