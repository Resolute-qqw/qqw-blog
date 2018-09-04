<?php
return [
    'redis'=>[
        'scheme'=>'tcp',
        'host'=>'127.0.0.1',
        'port'=>6379
    ],
    'db'=>[
        'host'=>'127.0.0.1',
        'dbname'=>'threelianxi',
        'user'=>'root',
        'pass'=>'142560',
        'charset'=>'utf8'
    ],
    'email'=>[
        'mode'=>'debug', //debug nodebug
        'port'=>'25',
        'host'=>'smtp.126.com',
        'name'=>'qqw142560@126.com',
        'pass'=>'qqw142560',
        'from_email'=>'qqw142560@126.com',
        'from_name'=>'圈圈丸'
    ],
];