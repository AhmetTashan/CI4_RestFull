<?php namespace App\Models\Authentication;

use CodeIgniter\Model;

class UserModel extends Model
{
	protected $table = 'users';
	protected $primaryKey = 'id';
	
	protected $returnType = 'array';
	protected $useSoftDeletes = true;
	
	protected $allowedFields = [
		'id', 'username', 'password', 'fullname',
		'email', 'activation_key', 'status'
	];
	
	protected $useTimestamps = true;
	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';
	protected $deletedField = 'deleted_at';
	
	/**
	 * @param object $data
	 * @return object
	 * @throws \ReflectionException
	 */
	public function login(object $data)
	{
		$feedback = (object)[];
		$feedback->status = 'error';
		$feedback->error_code = 'login';
		
		$builder = $this->select('id, email, fullname, username, password, status')
			->where('username', $data->username)
			->get()
			->getResultObject();
		
		if (count($builder) === 1) {
			
			if ($builder[0]->status === 'ACTIVE') {
				
				if (password_verify($data->password, $builder[0]->password)){
					
					$feedback->status = 'success';
					$feedback->message = 'Kullanıcı başarıyla giriş yaptı';
					
					$tokenModel = new \App\Models\Authentication\TokenModel();
					$feedback->token = $tokenModel->tokenCreate($builder[0]->id);
					
					unset($builder[0]->username);
					unset($builder[0]->password);
					unset($builder[0]->status);
					
					$feedback->user = $builder[0];
					$feedback->page_status = 202;
					
				} else {
					$feedback->code = 'PasswordNotMatch';
					$feedback->username = $data->username;
					$feedback->message = 'Girmiş olduğunuz parola uyuşmuyor.';
					$feedback->page_status = 203;
				}
				
			} elseif ($builder[0]->status === 'FORCEPASSRESET'){
				
				$feedback->code = 'UserForcePasswordReset';
				$feedback->message = 'Eposta adresinize gönderilen "Parolamı Sıfırla" butonuna tıklayın ve parolanızı sıfırladıktan sonra tekrar giriş yapmayı deneyin.';
				$feedback->page_status = 401;
				
			} elseif ($builder[0]->status === 'NOTACTIVE'){
				
				$feedback->code = 'UserNotActive';
				$feedback->message = 'Eposta adresinize gönderilen "Eposta Adresimi Doğrula" butonuna tıklandıktan sonra üyeliğiniz aktif olmaktadır.';
				$feedback->page_status = 401;
				
			} else {
				
				$feedback->code = 'UserBanned';
				$feedback->message = 'Yönetici tarafından üyeliğiniz askıya alınmıştır.';
				$feedback->page_status = 403;
				
			}
			
		} else {
			
			$feedback->code = 'UserNoFound';
			$feedback->message = 'Lütfen bilgilerinizi kontrol edin ve tekrar giriş yapın.';
			$feedback->page_status = 404;
		}
		
		return $feedback;
	}
	
}