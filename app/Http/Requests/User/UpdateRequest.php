<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                  => 'required|string|max:255|min:1',
            'email'                 => 'required|email',
            'avatar'                => 'image|max:10000',
            'addresses'             => 'nullable|array',
            'addresses.*'           =>  Rule::forEach(fn (array $addr) => [Rule::excludeIf(!$addr['address'])]),
            'addresses.*.address'   => 'nullable|string|max:255',
            'addresses.*.id'        => ["sometimes", Rule::exists('user_addresses', 'id')->where('user_id', $this->user->id)],
        ];
    }
}
