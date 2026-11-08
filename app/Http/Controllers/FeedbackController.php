<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'name.required' => 'Enter your name.',
            'email.required' => 'Enter your email so we can reply.',
            'email.email' => 'Enter a valid email address.',
            'message.required' => 'Write your feedback before sending.',
            'message.min' => 'Feedback must be at least 10 characters.',
        ]);

        Feedback::query()->create([
            ...$validated,
            'user_id' => $request->user()?->id,
        ]);

        return redirect()->to(route('about').'#feedback')
            ->with('feedback_status', 'Thank you! Your feedback was sent to the ARTFOLIO team.');
    }
}
