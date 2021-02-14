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
				'error_code' => 'login',
				'code' => 'LoginFormValidation',
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
		$feedback->error_code = 'register';
		
		$form_validation = \Config\Services::validation();
		
		$payload->username = $this->jsonData->username;
		$payload->password = $this->jsonData->password;
		$payload->fullname = $this->jsonData->fullname;
		$payload->email = $this->jsonData->email;
		
		if (!$form_validation->run((array)$payload, 'user_register_validation')) {
			
			$feedback->code = 'RegisterFormValidation';
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
			$feedback->code = 'ProblemRegisteringUser ';
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
			$data = [
				'status' => 'error',
				'error_code' => 'logout',
				'code' => 'LogoutFormValidation',
				'message' => $form_validation->getErrors()
			];
			return $this->respond($data, 203);
		}
		
		$tokenModel = $this->tokenModel->tokenDelete($payload);
		
		$page_status = $tokenModel->page_status;
		
		unset($tokenModel->page_status);
		
		return $this->respond($tokenModel, $page_status);
	}
	
	
	public function forgotPassword()
	{
		$payload = (object)[];
		$feedback = (object)[];


	}

	/**
	 * @return mixed
	 * @throws \ReflectionException
	 * @parametre current_password, password, confirm_password
	 */
	public function resetPassword()
	{
		$payload = (object)[];
		$feedback = (object)[];

		$feedback->status = 'error';
		$feedback->error_code = 'resetpassword';
		$page_code = 203;

		$payload->current_password = $this->jsonData->current_password;
		$payload->password = $this->jsonData->password;
		$payload->confirm_password = $this->jsonData->confirm_password;

		$form_validation = \Config\Services::validation();

		if (!$form_validation->run((array)$payload, 'user_reset_password')) {
			$feedback->code = 'ResetPasswordFormValidation';
			$feedback->message = $form_validation->getErrors();
			return $this->respond($feedback, 203);
		}

		helper('getuserid');
		$getUser = $this->model->asObject()->find(getUserId());


		if ( password_verify($payload->current_password, $getUser->password) ) {

			if ( $payload->current_password === $payload->password ) {
				$feedback->code = 'PasswordSame';
				$feedback->message = 'Mevcut paroladan farklı bir parola girin.';
			} else {
				$data = [
					'password' => password_hash($payload->password, PASSWORD_DEFAULT)
				];
				$builder = $this->model->update(getUserId(), $data);

				if ($builder) {
					$feedback->status = 'success';
					unset($feedback->error_code);
					$feedback->message = 'Yeni parolanız kayıt edildi.';
				} else {
					$feedback->code = 'CouldNotChangePassword';
					$feedback->message = 'Parola değiştirme gerçekleştirilemedi.';
				}
			}

		} else {
			$feedback->code = 'PasswordNotMatch';
			$feedback->message = 'Girmiş olduğunuz parola ile Mevcut Parolanız uyuşmuyor.';
		}

		return $this->respond($feedback, $page_code);
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