<?php namespace App\Validation\Rules;

trait UserValidation
{
    public $user_login_validation = [
        'username' => [
            'label' => 'Kullanıcı Adı',
            'rules' => 'required|alpha_numeric|min_length[3]|max_length[64]'
        ],
        'password' => [
            'label' => 'Parola',
            'rules' => 'required|min_length[6]'
        ]
    ];

    public $user_register_validation = [
        'username' => [
            'label' => 'Kullanıcı Adı',
            'rules' => 'required|alpha_numeric|min_length[3]|max_length[64]|is_unique[users.username]'
        ],
        'password' => [
            'label' => 'Parola',
            'rules' => 'required|min_length[6]'
        ],
        'fullname' => [
            'label' => 'Adınız ve Soyadınız',
            'rules' => 'required|alpha_space_tr|min_length[3]|max_length[64]'
        ],
        'email' => [
            'label' => 'E-Posta Adresi',
            'rules' => 'required|valid_email|is_unique[users.email]'
        ]
    ];

    public $user_logout_validation = [
        'token' => [
            'label' => 'Anahtar Değeri',
            'rules' => 'required|alpha_numeric|length[64]',
            'errors' => [
                'required' => '{field} boş bırakılamaz.',
                'alpha_numeric' => '{field} yalnız harf ve rakam içerebilir.',
                'length' => '{field} ({param}) karekter olmalıdır.'
            ]
        ],
        'user_id' => [
            'label' => 'Kullanıcı Kimlik Numarası',
            'rules' => 'required|numeric|max_length[20]',
            'errors' => [
                'required' => '{field} boş bırakılamaz.',
                'numeric' => '{field} yalnız rakam içerebilir.',
                'max_length' => '{field} {param} karekterden fazla olamaz.'
            ]
        ]
    ];
}