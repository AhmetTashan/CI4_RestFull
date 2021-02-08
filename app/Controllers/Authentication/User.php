<?php namespace App\Controllers\Authentication;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class User extends BaseController
{
	use ResponseTrait;
	
	public $jsonData;
	private $model;
	private $tokenModel;
	
	public function __construct()
	{
		$this->model = new \App\Models\Authentication\UserModel();
		$this->tokenModel = new \App\Models\Authentication\TokenModel();
		$this->jsonData = (object)json_decode(file_get_contents("php://input"));
	}
	
	/**
	 * @name login
	 * @return mixed
	 * @throws \ReflectionException
	 * @parametre username, password
	 */
	public function login()
	{
		helper('form');
		$payload = (object)[];
		
		$form_validation = \Config\Services::validation();
		
		$payload->username = $this->jsonData->username;
		$payload->password = $this->jsonData->password;
		
		if (!$form_validation->run((array)$payload, 'user_login_validation')) {
			$data = [
				'status' => 'error',
				'code' => 'login',
				'error_code' => 'LoginFormValidation',
				'message' => $form_validation->getErrors()
			];
			
			return $this->respond($data, 203);
		}
		
		$feedback = $this->model->login($payload);
		
		$page_status = $feedback->page_status;
		unset($feedback->page_status);
		
		return $this->respond($feedback, $page_status);
	}
	
	/**
	 * @name register
	 * @return mixed
	 * @throws \ReflectionException
	 * @parametre username, password, fullname, email
	 */
	public function register()
	{
		helper('form');
		$payload = (object)[];
		
		$feedback = (object)[];
		$feedback->status = 'error';
		$feedback->code = 'register';
		
		$form_validation = \Config\Services::validation();
		
		$payload->username = $this->jsonData->username;
		$payload->password = $this->jsonData->password;
		$payload->fullname = $this->jsonData->fullname;
		$payload->email = $this->jsonData->email;
		
		if (!$form_validation->run((array)$payload, 'user_register_validation')) {
			
			$feedback->error_code = 'RegisterFormValidation';
			$feedback->message = $form_validation->getErrors();
			
			return $this->respond($feedback, 203);
		}
		
		$payload->password = password_hash($payload->password, PASSWORD_DEFAULT);
		
		$user_id = $this->model->insert($payload);
		
		if ($user_id > 0) {
			
			$feedback->status = 'success';
			$feedback->message = 'Kullanıcı başarıyla kayıt oldu.';
			$feedback->user["id"] = $user_id;
			$feedback->user["email"] = $payload->email;
			$feedback->user["fullname"] = $payload->fullname;
			$page_status = 202;
			
		} else {
			$feedback->error_code = 'ProblemRegisteringUser ';
			$feedback->message = 'Kullanıcı kayıt olurken bir sorun oluştu. Lütfen daha sonra tekrar deneyin.';
			$page_status = 203;
		}
		
		return $this->respond($feedback, $page_status);
	}
	
	/**
	 * @name logout
	 * @return mixed
	 * @parametre token, user_id
	 */
	public function logout()
	{
		$payload = (object)[];
		$payload->token = $this->jsonData->token;
		$payload->user_id = $this->jsonData->user_id;
		
		$form_validation = \Config\Services::validation();
		
		if (!$form_validation->run((array)$payload, 'user_logout_validation')) {
			return $this->respond($form_validation->getErrors(), 203);
		}
		
		$tokenModel = $this->tokenModel->tokenDelete($payload);
		
		$page_status = $tokenModel->page_status;
		
		unset($tokenModel->page_status);
		
		return $this->respond($tokenModel, $page_status);
	}
	
	
	public function forgotPassword()
	{
		
	}
	
	
	public function resetPassword()
	{
		$payload = (object)[];
		$feedback = (object)[];

		$feedback->status = 'error';
		$feedback->code = 'resetpassword';

		$payload->current_password = $this->jsonData->current_password;
		$payload->password = $this->jsonData->password;
		$payload->confirm_password = $this->jsonData->confirm_password;

		$form_validation = \Config\Services::validation();
/*
		if (!$form_validation->run((array)$payload, 'user_reset_password')) {
			$feedback->error_code = 'ResetPasswordFormValidation';
			$feedback->message = $form_validation->getErrors();
			return $this->respond($feedback, 203);
		}*/

		helper('getuserid');
		$getUser = $this->model->asObject()->find(getUserId());


		// TODO: karşılaştırma operatoru sorunu burada kaldık
		if ( $payload->current_password !== $payload->password ) {
			echo "başarılı";
		} else {
			echo "aynı olmamalı";
		}

		/*if ( password_verify($payload->current_password, $getUser->password) ) {

			if ( $payload->current_password !== $payload->password ) {
				echo "başarılı";
			} else {
				echo "aynı olmamalı";
			}

		} else {
			$feedback->error_code = 'PasswordNotMatch';
			$feedback->message = 'Girmiş olduğunuz parola ile Mevcut Parolanız uyuşmuyor';
			return $this->respond($feedback, 203);
		}*/

	}
	
	
	public function email()
	{
		$email = \Config\Services::email();
		
		$email->setFrom('test@tashan.cc', 'Ahmet Tashan');
		$email->setTo('ahmettashann@gmail.com');
		$email->mailType = 'text';
		
		$email->setSubject('Email Test');
		$email->setMessage("Email content");
		
		if ($email->send()) {
			echo "mail gönderildi. :)";
        } else {
            echo $email->printDebugger();
		}
	}
}