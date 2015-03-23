                    <li class="comment">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <img src="{%AVATAR%}" alt="avatar" class="imageborder alignleft" style="width: 100px; height: 100px;">
                                <p>{%COMMENT%}</p>
                            </div>
                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-sm-8">
                                        <i class="fa fa-user"> </i> {%NAME%} <i class="fa fa-clock-o"></i> {%DATE%}
                                        <i class="fa fa-globe"> </i> {%WEBSITE%}
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-4">
                                        <div class="pull-right">{%REPLY_FLAG%}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    [reply]
                    <ul class="list-unstyled sub-comments">
                            <li class="comment">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <p>{%REPLY%}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        [/reply]