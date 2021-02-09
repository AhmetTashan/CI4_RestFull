## *//---------------------// **Model** //---------------------//*

### **UserModel**

#### UserModel.php

##### -- login *(error_code)*

- **PasswordNotMatch** *(code)*
Girmiş olduğunuz parola uyuşmuyor

- **UserForcePasswordReset** 
Eposta adresinize gönderilen "Parolamı Sıfırla" butonuna tıklayın ve parolanızı sıfırladıktan sonra tekrar giriş yapmayı deneyin.

- **UserNotActive** 
Eposta adresinize gönderilen "Eposta Adresimi Doğrula" butonuna tıklandıktan sonra üyeliğiniz aktif olmaktadır.

- **UserBanned**
Yönetici tarafından üyeliğiniz askıya alınmıştır.

- **UserNoFound**
Lütfen bilgilerinizi kontrol edin ve tekrar giriş yapın.

//---------------------//

### **TokenModel**

#### TokenModel.php

##### -- token *(error_code)*

- **TokenTimeExpire** *(code)*
  

- **TokenNotFoundInDatabase**

- **TokenNotDelete**
Oturumunuz kapatılıtken bir sorun oluştu.

- **TokenNotFound**


## *//---------------------// **Controller** //---------------------//*

### **User**

#### User.php

##### -- login *(error_code)*

- **LoginFormValidation** *(code)*


##### -- register 

- **RegisterFormValidation**

- **ProblemRegisteringUser**
Kullanıcı kayıt olurken bir sorun oluştu. Lütfen daha sonra tekrar deneyin.

##### -- logout

- **LogoutFormValidation**


##### -- resetpassword

- **ResetPasswordFormValidation**

- **PasswordSame**
Mevcut paroladan farklı bir parola girin.

- **CouldNotChangePassword**
  Parola değiştirme gerçekleştirilemedi.

- **PasswordNotMatch**
Girmiş olduğunuz parola ile Mevcut Parolanız uyuşmuyor.