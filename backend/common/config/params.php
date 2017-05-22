<?php
return [
    // 用户token有效时间(秒)
    'user_verificationCodeTokenExpire' => 300,// 5分钟
    'user_identityByAccessTokenExpire' => 2592000,// 30天

    // 网页登陆cookie或session 有效时间
    'user_loginExpire'=>3600 * 24 * 365,

    'page_size' => 10, // 默认分页大小

    /**
     * common/base/ControllerBase的success和error方法配置参数
     */
    // 'message_success_tpl' => 'message', 成功信息模板
    // 'message_error_tpl' => 'message', 错误信息模板
    // 'message_reader_method' => 'reader', 错误渲染视图方法模板
    // 'message_reader_layout' =>'main' , 渲染视图布局
];
