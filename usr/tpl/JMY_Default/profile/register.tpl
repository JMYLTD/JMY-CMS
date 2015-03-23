                [open]
                <div class="panel panel-primary">
                    <div class="panel-heading">Регистрация пользователя</div>
                    <div class="panel-body">
                        <form role="form" action="profile/register"  method="post" enctype="multipart/form-data">
                          <div class="form-group">
                            <label>Логин</label>
                            <input type="text" name="user_login" id="user_login" class="form-control" placeholder="Используйте буквы в диапазоне (a-z) и цифры (0-9)" onchange="javascript:check_login(gid('user_login').value, 'check_result');">
                            <div id="check_result"></div>
                          </div>
                          <div class="form-group">
                            <label for="exampleInputEmail1">E-mail</label>
                            <input name="email" id="email" type="text" class="form-control" placeholder="На этот адрес придет ссылка для активации">
                          </div>
                          <div class="form-group">
                            <label for="exampleInputPassword1">Пароль</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Пароль должен содержать минимум 6 символов" onblur="javascript:check_password(this.value, 'repassword');">
                          </div>
                          <div class="form-group">
                            <label for="exampleInputPassword1">Повторите пароль</label>
                            <input type="password" name="repassword" id="repassword" class="form-control" placeholder="Введённые пароли должны совпадать" onblur="javascript:check_password(this.value, 'password');">
                            <div id="checkPassword"></div>
                          </div>
                          <div class="form-group">
                            <label>Проверочный код</label>
                            {%CAPTCHA%}
                          </div>
                          <div class="form-group">
                            <label>Введите код с картинки</label>
                            <input type="text" name="securityCode" id="securityCode" class="form-control" style="width:120px">
                            <div id="check_result"></div>
                          </div>
                          <div class="form-group">
                            <label><a href="javascript:void(0)" onclick="showhide('addition')"><font size="3pt">Заполнить дополнительные данные</font></a></label>
                          </div>
                          <div id="addition" style="display:none;">
                          <div class="form-group">
                            <label>Ваш ICQ</label>
                            <input type="text" name="icq" class="form-control">
                            <div id="check_result"></div>
                          </div>
                          <div class="form-group">
                            <label>Ваша фамилия</label>
                            <input type="text" name="family" class="form-control">
                            <div id="check_result"></div>
                          </div>
                          <div class="form-group">
                            <label>Ваше настоящее имя</label>
                            <input type="text" name="name" class="form-control">
                            <div id="check_result"></div>
                          </div>
                          <div class="form-group">
                            <label>ЛВаше отчество</label>
                            <input type="text" name="ochestvo" class="form-control">
                            <div id="check_result"></div>
                          </div>                          
                          <div class="form-group">
                            <label for="select">Ваш пол</label>
                              <select class="form-control" name="sex">
                                <option value="0" >-- Скрыто --</option>
                                <option value="1" >Мужской</option>
                                <option value="2" >Женский</option>
                              </select>
                          </div>
                          </div>
                          <button type="submit" id="submit" class="btn btn-ar btn-primary">Зарегистрироваться</button>
                        </form>
                    </div>
                </div>
                [/open]
<script type="text/javascript">
function check_password(repass,id){password=gid(id).value;if(password.length>0&&repass.length>0){if(password==repass){text="<font color=\"green\">Пароли совпадают, всё в порядке.</font>";gid('submit').disabled=false}else{text="<font color=\"red\">Пароли не совпадают! Проверьте правильность ввода!</font>";gid('submit').disabled=true}gid('checkPassword').innerHTML=text}else{gid('checkPassword').innerHTML=''}}
function check_login(val,id){if(val.length>3){AJAXEngine.setPostVar('uname',encodeURI(val,true));AJAXEngine.sendRequest('ajax.php?do=check_login',id)}}
</script>                