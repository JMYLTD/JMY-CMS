[open]
<div class="panel panel-primary">
                    <div class="panel-heading">Востановление пароля</div>
                    <div class="panel-body">
                        <form role="form" name="form" method="post" action="" enctype="multipart/form-data" onsubmit="showload();">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input name="email" id="email" type="text" class="form-control" placeholder="Введите ваш email, зарегестрированный на сайте">
                          </div>
                          <div class="form-group">
                            <label for="exampleInputPassword1">Проверочный код</label>
                            {%CAPTCHA%}
                          </div>
                          <div class="form-group">
                            <label for="exampleInputPassword1">Введите код с картинки</label>
                            <input name="securityCode" id="securityCode" type="text" class="form-control" style="width:120px">
                          </div>
                          <button type="submit" id="submit" class="btn btn-ar btn-primary">Отправить</button>
                        </form>
                    </div>
                </div>
                [/open]