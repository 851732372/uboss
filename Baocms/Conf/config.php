<?php
define('TODAY', date("Y-m-d")); 
$dbconfigs = require  BASE_PATH.'/'.APP_NAME.'/Conf/db.php';
$configs =  array(

    //'配置项'=>'配置值'

    'APP_GROUP_LIST' => 'Admin,Shangjia,Mobile,Store,Delivery,App,Wuye,Mcenter,Member,Pchome,Api', //项目分组设定

     'DEFAULT_GROUP'  => 'Pchome', //默认分组

    'SHOW_PAGE_TRACE' => true,

    //SESSION 的设置

    'SESSION_AUTO_START'    => true,

    'SESSION_TYPE'          => 'DB',

    'DEFAULT_APP'           => 'Baocms',
//    'SESSION_OPTIONS'       =>  array('save_path'=>'/'),
//    'SESSION_OPTIONS'       =>  array('use_trans_sid'=>1,'use_only_cookies'=>0),

    

    //URL设置

    'URL_MODEL'            => 2,

    'URL_HTML_SUFFIX'      => '.html',

    'URL_ROUTER_ON'        => true,

    'URL_CASE_INSENSITIVE' => true, //url不区分大小写

    'URL_ROUTE_RULES'      => array(

    ), 

    'APP_SUB_DOMAIN_DEPLOY' => false,



    //默认系统变量

    'VAR_GROUP'            => 'g',

    'VAR_MODULE'           => 'm',

    'VAR_ACTION'           => 'a',

    'VAR_TEMPLATE'         => 'theme',

    

    //模版设置相关

    'DEFAULT_THEME'         => 'default',

    'TMPL_L_DELIM'          => '<{',

    'TMPL_R_DELIM'          => '}>', 

    'TMPL_ACTION_SUCCESS'   => 'public/dispatch_jump',

    'TMPL_ACTION_ERROR'     => 'public/dispatch_jump',

     

    'TAGLIB_LOAD'           => true,

    'APP_AUTOLOAD_PATH'     => '@.TagLib',

    'TAGLIB_BUILD_IN'       => 'Cx,Calldata',

    // 自定义配置文件
    'LOAD_EXT_CONFIG'       => 'remind',
);

return array_merge($configs,$dbconfigs);

?>