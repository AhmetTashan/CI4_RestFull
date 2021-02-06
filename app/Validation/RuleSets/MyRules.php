<?php namespace App\Validation\RuleSets;

class MyRules
{
	/**
	 * türkçe harfler için "alpha_space" özelliği
	 *
	 * @param string|null $value
	 * @return bool
	 */
	public function alpha_space_tr(string $value = null): bool
	{
		if ($value === null)
		{
			return true;
		}
		
		return (bool) preg_match('/^[A-Z ĞÜŞİÖÇğüşıöç]+$/i', $value);
	}
	
	public function length(string $str = null, string $val): bool
	{
		return ($val == mb_strlen($str));
	}
}