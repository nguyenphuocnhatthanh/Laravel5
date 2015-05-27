<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class FormUsers extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
        $email = 'required|email|unique:users,email';
        if($this->has('id')) $email .= ','.$this->get('id');
		return [
			'name'  => 'required',
            'email' => $email,
            'roles'  => 'required'
		];
	}

}
