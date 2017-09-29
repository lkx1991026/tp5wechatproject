<?php
namespace app\admin\validate;

use think\Validate;

class Repaire extends Validate{
    protected $rule = [
        ['username', 'require', '姓名不能为空'],
        ['tel', 'require', '电话号码不能为空'],
        ['addr', 'require', '地址不能为空'],
        ['title', 'require', '标题不能为空'],
        ['content', 'require', '内容不能为空'],
    ];
}