[open]
<div class="panel panel-primary">
                    <div class="panel-heading">Вход</div>
                    <div class="panel-body">
                        <form role="form" action="/profile/login" method="post" onsubmit="showload();">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Логин</label>
                            <input name="nick" id="nick" type="text" class="form-control" placeholder="Введите ваш логин для входа">
                          </div>
                          <div class="form-group">
                            <label for="exampleInputPassword1">Пароль</label>
                            <input name="password" id="password" type="password" class="form-control" placeholder="Введите ваш пароль указанный при регистрации">
                          </div>
                          <button type="submit" id="submit" class="btn btn-ar btn-primary">Войти</button>
                        </form>
                    </div>
                </div>
                [/open]