<?php
namespace app\admin\controller;

use think\Db;
use think\Request;

class Repaire extends Admin
{
    public function index()
    {
        $list=Db::name('repaire')->paginate(2);

        $this->assign('list', $list);


        return $this->fetch();
    }

    public function add()
    {
        if (request()->isPost()) {
            $repaire = model('repaire');
            $post_data = \think\Request::instance()->post();
            if(isset($post_data['home'])){
                unset($post_data['home']);
                $home=true;

            }
            //自动验证
            $validate = validate('repaire');
            if (!$validate->check($post_data)) {
                return $this->error($validate->getError());
            }
            $data = $repaire->create($post_data);
            if ($data) {
//                var_dump(Request::instance()->dispatch());exit;

                $this->success('新增成功', url($home?'home/index/index.html':'index'));

                //记录行为
//                action_log('update_channel', 'channel', $data->id, UID);
            } else {
                $this->error($repaire->getError());
            }
        }
        return $this->fetch('add');
    }

    public function edit($id=0){
        if(request()->isPost()){
            $postdata = \think\Request::instance()->post();
            $validate=validate('repaire');
            if(!$validate->check($postdata)){
                return $this->error($validate->getError());
            }
            $repaire = Db::name("repaire");
            $postdata['update_time']=time();
            $data = $repaire->update($postdata);
            if($data !== false){
                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        }else{
            $info=Db::name('repaire')->find($id);
            $this->assign('info',$info);
            return $this->fetch('add');
        }
    }
    public function del(){
        $id = array_unique((array)input('id/a',0));
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(\think\Db::name('repaire')->where($map)->delete()){
            //记录行为
            action_log('update_repaire', 'repaire', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
    public function status(){
        $id=Request::instance()->get('id');
        $data=Db::name('repaire')->find($id);
        $data['status']=$data['status']==0?1:0;
        if(Db::name('repaire')->update($data)){
            //记录行为
            action_log('update_repaire', 'repaire', $id, UID);
            $this->success('修改成功');
        } else {
            $this->error('修改失败！');
        }

    }
}