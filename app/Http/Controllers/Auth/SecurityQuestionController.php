<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SecurityQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class SecurityQuestionController extends Controller
{
    /**
     * Show the security questions verification form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showVerificationForm(Request $request)
    {
        $email = $request->input('email');
        
        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Email is required to verify security questions.']);
        }
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'No account found with this email address.']);
        }
        
        if (!$user->hasSecurityQuestions()) {
            // User hasn't set up security questions, proceed with traditional reset
            $status = Password::sendResetLink(['email' => $email]);
            
            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
        }
        
        $securityQuestions = [
            1 => $user->securityQuestion1,
            2 => $user->securityQuestion2
        ];
        
        return view('auth.verify-security-questions', [
            'email' => $email,
            'securityQuestions' => $securityQuestions
        ]);
    }
    
    /**
     * Verify the security questions answers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyAnswers(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'security_answer_1' => 'required|string',
            'security_answer_2' => 'required|string',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email address.']);
        }
        
        // Check if answers match
        $answer1Matches = $request->security_answer_1 === $user->security_answer_1;
        $answer2Matches = $request->security_answer_2 === $user->security_answer_2;
        
        if (!$answer1Matches || !$answer2Matches) {
            return back()->withErrors(['security_answers' => 'The security answers provided do not match our records.']);
        }
        
        // Generate password reset token
        $token = Password::createToken($user);
        
        // Redirect to password reset form with token and email
        return redirect()->route('password.reset', ['token' => $token])
            ->with(['email' => $request->email]);
    }
}
