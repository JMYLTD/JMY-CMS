<script language="javascript">function checkAddPost(){var err = 0;gid('titleErr').innerHTML = '';gid('postTextErr').innerHTML = '';if(gid('title').value == ''){err = 1;gid('titleErr').innerHTML = '<font color="red">Вы не заполнили заголовок новости!</font>';} if(gid('short').value == ''){	err = 1;gid('postTextErr').innerHTML = '<font color="red">Вы не заполнили анонс новости!</font>';}if(err == 1){alert('Одно из обязательных полей не заполнено, проверте правильность ввода данных!');return false;}else{return true;}}</script>
<div class="panel panel-primary">
<div class="panel-heading">[lang:_ADD_NEWS]</div>
<div class="panel-body">
<form role="form" action="news/savePost" method="post" onsubmit="return checkAddPost();">
                          <div class="form-group">
                            <label for="exampleInputTitle">Заголовок новости</label>
                            <input name="title" type="text" id="title" class="form-control" placeholder="Название вашей новости (не более 35 символов)">
                          </div>
                          <div class="form-group">
                            <label for="select">Выберите категорию для публикации</label>
                              <select class="form-control" name="category[]">
                                <option value="0">Без категории</option>
                                {%CATS%}
                              </select>
                          </div>
                          <div class="form-group">
                            <label for="multiple-select">Связанные категории</label>
                              <select multiple class="form-control" name="category[]">
                                {%CATS%}
                              </select>
                          </div>
                          <div class="form-group">
                            <label for="exampleInputnews1">Краткая новость</label>
                            {%BB_SHORT%}
                          </div>
                          <div class="form-group">
                            <label for="exampleInputnews2">Полная новость</label>
                            {%BB_FULL%}
                          </div>
                          {%XFILEDS%}
                          [fileupload]
                          <div class="form-group">
                            <label for="exampleInputnews1" onclick="uploaderStart(); showhide('file_upload', true);" style="text-decoration:underline; cursor:pointer;">Файловый редактор</label>
                            <div style="display:none;" id="file_upload">{%FILE_UPLOAD%}</div>
                          [/fileupload]
                          <button type="submit" class="btn btn-ar btn-primary">[lang:_ADD_NEWS]</button>
                        </form>
                    </div>
                </div>