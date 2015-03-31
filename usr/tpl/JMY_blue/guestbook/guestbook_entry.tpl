<section class="comment-form" id="comments">
                <h2 class="section-title">Ваш отзыв</h2>
                <form role="form" action="guestbook/send" id="commentform" method="post" novalidate="" onsubmit="return validate(this)" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="inputName">Имя *</label>
                        <input id="author" name="name" type="text" aria-required="true" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Email *</label>
                        <input id="email" name="email" type="text" aria-required="true" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Сайт *</label>
                        <input id="url" name="site" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                            <label for="select">Ваш пол *</label>
                              <select class="form-control" name="gender">
                                <option value="0" >-- Скрыто --</option>
                                <option value="1" >Мужской</option>
                                <option value="2" >Женский</option>
                              </select>
                          </div>
                    <div class="form-group">
                        <label for="inputMessage">Сообщение *</label>
                        <textarea class="form-control" id="comment" name="message" aria-required="true" rows="6"></textarea>
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
                    <button name="submit" type="submit" id="submit" class="btn btn-ar pull-right btn-primary">Отправить</button>
                </form>
            </section>