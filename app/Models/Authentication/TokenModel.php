<?php namespace App\Models\Authentication;

use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class TokenModel extends Model
{
	protected $table      = 'access_token';
	protected $primaryKey = 'id';
	
	protected $returnType     = 'array';
	protected $useSoftDeletes = false;
	
	protected $allowedFields = ['access_token', 'user_id', 'expires'];
	
	protected $useTimestamps = false;
	
	/**
	 * @param $token
	 * @return object
	 * @throws \Exception
	 */
	public function tokenControl($token)
	{
		$feedback = (object)[];
		$feedback->status = 'error';
		$feedback->error_code = 'token';
		$feedback->message = 'Oturumunuzun süresi dolmuştur.';
		
		$control = $this
			->select('access_token, expires')
			->where('access_token', $token)
			->get()
			->getResultArray();
		
		
		if (count($control) > 0) {
			
			$expiresTime = Time::parse($control[0]["expires"]);
			$now = Time::now();
			
			if ($now->getTimestamp() < $expiresTime->getTimestamp()) {  // status success (token gecerli)
				
				$feedback->status = "success";
				unset($feedback->error_code);
				unset($feedback->message);
				
			} else {  // status error (token süresi geçmiş)
				
				$feedback->code = 'TokenTimeExpire';
			}
			
		} else {
			$feedback->code = 'TokenNotFound';
		}
		
		return $feedback;
	}
	
	/**
	 * @param $user_id
	 * @return false|string
	 * @throws \ReflectionException
	 */
	public function tokenCreate($user_id)
	{
		helper('token');
		$token = createToken();
		
		$expiresTime = Time::now()->addHours(16);
		
		$data = [
			'access_token' => $token,
			'user_id' => $user_id,
			'expires' => $expiresTime
		];
		
		$this->insert($data);
		
		return $token;
	}

	/**
	 * @param $payload
	 * @return object
	 * @throws \ReflectionException
	 */
	public function tokenDelete($payload)
	{
		$feedback = (object)[];
		$feedback->status = 'success';
		$feedback->code = 'token';
		$feedback->message = 'Oturumunuz kapanmıştır.';
		$feedback->page_status = 202;
		
		$tokenSelect = $this->select('id, expires')
			->where('access_token', $payload->token)
			->where('user_id', $payload->user_id)
			->get()
			->getResultObject();
		
		if (count($tokenSelect) > 0) {
			
			$expiresTime = Time::parse($tokenSelect[0]->expires);
			$now = Time::now();
			
			if ($now->getTimestamp() < $expiresTime->getTimestamp()) {

				$data = [
					'expires' => $now->toDateTimeString()  // 2016-03-09 12:00:00
				];
				
				$builder = $this->update(['id' => $tokenSelect[0]->id], $data);
				
				if ($builder != 1) {
					
					$feedback->status = 'error';
					$feedback->code = 'token';
					$feedback->code_code = 'token';
					$feedback->message = 'Oturumunuz kapatılıtken bir sorun oluştu.';
					$feedback->page_status = 203;
				}

			}
		}
		
		return $feedback;
	}

	/**
	 * @param $token
	 * @return mixed
	 */
	public function user_id($token)
	{
		$control = $this
			->select('user_id')
			->where('access_token', $token)
			->get()
			->getResultObject();

		return $control[0]->user_id;
	}
}