<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:12288',
            'social_instagram' => [
                'nullable',
                'url',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
                        $allowed = ['instagram.com', 'www.instagram.com', 'instagr.am', 'www.instagr.am', 'm.instagram.com'];
                        if (!in_array($host, $allowed, true)) {
                            $fail('The Instagram link must be a valid Instagram URL (e.g. https://instagram.com/username).');
                        }
                    }
                },
            ],
            'social_facebook' => [
                'nullable',
                'url',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
                        $allowed = ['facebook.com', 'www.facebook.com', 'fb.com', 'www.fb.com', 'm.facebook.com', 'web.facebook.com', 'fb.watch'];
                        if (!in_array($host, $allowed, true)) {
                            $fail('The Facebook link must be a valid Facebook URL (e.g. https://facebook.com/page).');
                        }
                    }
                },
            ],
            'social_tiktok' => [
                'nullable',
                'url',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
                        $allowed = ['tiktok.com', 'www.tiktok.com', 'vm.tiktok.com', 'm.tiktok.com', 'vt.tiktok.com'];
                        if (!in_array($host, $allowed, true)) {
                            $fail('The TikTok link must be a valid TikTok URL (e.g. https://tiktok.com/@username).');
                        }
                    }
                },
            ],
            'social_youtube' => [
                'nullable',
                'url',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
                        $allowed = ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtu.be', 'www.youtu.be'];
                        if (!in_array($host, $allowed, true)) {
                            $fail('The YouTube link must be a valid YouTube URL (e.g. https://youtube.com/channel).');
                        }
                    }
                },
            ],
            'password' => 'nullable|string|min:6|confirmed',
            'current_password' => 'nullable|string',
        ];

        if ($this->user()->role === 'user') {
            $rules['country'] = 'required|string|max:100';
            $rules['description'] = 'nullable|string';
        }

        return $rules;
    }
}
