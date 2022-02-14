<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function rules()
    {
        return [
            'site_name' => 'required',
            'url' => 'required',
            'api_token' => 'required'
        ];
    }
}