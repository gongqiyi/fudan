<?php
return [
    'page_size' => 15, // 默认分页大小

    /**
     * RBAC权限配置
     */
    'USER_AUTH_ON'=>true, // 是否开启认证
    'USER_AUTH_KEY' => 'adminId', //登陆认证识别号
    'ADMIN_AUTH_KEY' =>'dookaySuper', // 超级管理员识别
    'SUPER_ADMIN_NAME'=>'dookaysuper', //超级管理员名称
    'NOT_AUTH_ACTION' => 'manage/passport/login,manage/site/captcha,manage/site/error,manage/prototype/category/expand_nav,manage/slide/category/expand_nav,manage/prototype/form/expand_nav', //无需认证方法,例如 backend-site-index,backend-user-index

];
