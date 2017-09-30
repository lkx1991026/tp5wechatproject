<?php
namespace app\admin\controller;


use app\admin\controller\Admin;
use think\Db;
use think\Request;

class Notice extends Admin{
    public  function index($home=0){
        $list=Db::name('notice')->paginate(4);
        $page=$list->render();
        $this->assign('pager',$page);
        $this->assign('info',$list);

        return $this->fetch('index');
    }
    public function add($id=0){

        if($id!=0){
            $data=Db::name('notice')->find($id);
            $this->assign('info',$data);
        }
        if(Request::instance()->isPost()) {
            $data=Request::instance()->post();
            $data['author']=Request::instance()->session('user_auth.username');
            unset($data['parse']);
            $file = request()->file('image');
            if($file){
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info) {
                    // 成功上传后 获取上传信息
                    // 输出 jpg
                    $data['img_path']=$info->getSavename();
                    $data['img_path']='/uploads/'.$data['img_path'];
                } else {
                    // 上传失败获取错误信息
                    echo $this->error($file->getError());
                }
            }
            $db=model('notice');
            if($data['id']){
                $rst=$db->update($data);
            }else{
                $rst=$db->create($data);
            }
            if($rst){
                $this->success($data['id']?'修改成功':'新增成功','index');

                //记录行为
//                action_log('update_channel', 'channel', $data->id, UID);
            } else {
                $this->error($db->getError());
            }
        }
        return $this->fetch('add');
    }
    public function test(){
        var_dump(Request::instance()->session('user_auth.username'));
    }
}