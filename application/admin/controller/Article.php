<?php
namespace app\admin\controller;

use app\common\model\Article as ArticleModel;
use app\common\model\Category as CategoryModel;
use app\common\controller\AdminBase;
use think\Session;
use think\Db;
/**
 * 文章管理
 * Class Article
 * @package app\admin\controller
 */
class Article extends AdminBase
{
    protected $article_model;
    protected $category_model;

    protected function _initialize()
    {
        parent::_initialize();
        $this->article_model  = new ArticleModel();
        $this->category_model = new CategoryModel();

        $category_level_list = $this->category_model->getLevelList();
        $this->assign('category_level_list', $category_level_list);
    }

    /**
     * 文章管理
     * @param int    $cid     分类ID
     * @param string $keyword 关键词
     * @param int    $page
     * @return mixed
     */
    public function index($cid = 0, $keyword = '', $page = 1)
    {
        $map   = [];
        $field = 'id,title,cid,author,reading,status,publish_time,sort';

        if ($cid > 0) {
            $category_children_ids = $this->category_model->where(['path' => ['like', "%,{$cid},%"]])->column('id');
            $category_children_ids = (!empty($category_children_ids) && is_array($category_children_ids)) ? implode(',', $category_children_ids) . ',' . $cid : $cid;
            $map['cid']            = ['IN', $category_children_ids];
        }

        if (!empty($keyword)) {
            $map['title'] = ['like', "%{$keyword}%"];
        }

        $article_list  = $this->article_model->field($field)->where($map)->order(['sort' => 'DESC','publish_time' => 'DESC'])->paginate(15, false, ['page' => $page]);
        $category_list = $this->category_model->column('name', 'id');

        return $this->fetch('index', ['article_list' => $article_list, 'category_list' => $category_list, 'cid' => $cid, 'keyword' => $keyword]);
    }

    /**
     * 添加文章
     * @return mixed
     */
    public function add()
    {
        $this->assign('save_cid',Session::get('save_cid'));
        return $this->fetch();
    }

    /**
     * 保存文章
     */
    public function save()
    {
        if ($this->request->isPost()) {
            $data            = $this->request->param();
            $validate_result = $this->validate($data, 'Article');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->article_model->allowField(true)->save($data)) {
                    Session::set('save_cid',$data['cid']);

                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            }
        }
    }

    /**
     * 编辑文章
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $article = $this->article_model->find($id);

        return $this->fetch('edit', ['article' => $article]);
    }

    /**
     * 更新文章
     * @param $id
     */
    public function update($id)
    {
        if ($this->request->isPost()) {
            $data            = $this->request->param();
            $validate_result = $this->validate($data, 'Article');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->article_model->allowField(true)->save($data, $id) !== false) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            }
        }
    }

    /**
     * 删除文章
     * @param int   $id
     * @param array $ids
     */
    public function delete($id = 0, $ids = [])
    {
        $id = $ids ? $ids : $id;
        if ($id) {
            if ($this->article_model->destroy($id)) {
                $this->success('删除成功');
            } else {
                $this->error('删除失败');
            }
        } else {
            $this->error('请选择需要删除的文章');
        }
    }

    /**
     * 文章审核状态切换
     * @param array  $ids
     * @param string $type 操作类型
     */
    public function toggle($ids = [], $type = '')
    {
        $data   = [];
        $status = $type == 'audit' ? 1 : 0;

        if (!empty($ids)) {
            foreach ($ids as $value) {
                $data[] = ['id' => $value, 'status' => $status];
            }
            if ($this->article_model->saveAll($data)) {
                $this->success('操作成功');
            } else {
                $this->error('操作失败');
            }
        } else {
            $this->error('请选择需要操作的文章');
        }
    }


    //常规报表导出
    public function daochu()
    {
        $list = Db::name('article')->field('title,author,status,create_time,publish_time')->select();

        // // exportExcel_one方法    可用
        // $savefile = "文章报表";
        // $fileheader = ['标题','作者','状态',"创建时间","发布时间"];
        // $sheetname = "报表下标";
        // $widthArr = ['A'=>80];//设置某些列的宽度
        // $arr_hb = ['B' => 'author', 'c' => 'status'];
        // exportExcel_one($list, $savefile, $fileheader, $sheetname,$widthArr,$arr_hb);

        $title = ['标题','作者','状态',"创建时间","发布时间"];
        $filename = "文章报表";
        $sheetname = "报表下标";
        $cellNames = ['A', 'B', 'C', 'D', 'E'];
        $arr_hb = ['B' => 'author', 'c' => 'status'];
        $widthArr = ['A'=>80,'C'=>50];
        Export($list, $title, $filename, $sheetname, $cellNames, $arr_hb, $widthArr);
        // Export($list, $title, $filename, $cellNames);

        // // exportOrderExcel方法
        // $title = "文章报表exportOrderExcel";
        // $cellName = ['标题','作者','状态',"创建时间","发布时间"];
        // exportOrderExcel($title,$cellName,$list);


        // //exportExcel_two方法    php7版本以上不可用
        // $expTableData = $list;
        // $expCellName = array(
        //         ['title','标题',30],
        //         ['author','作者',20],
        //         ['status','状态',10],
        //         ['create_time','创建时间',20],
        //         ['publish_time','发布时间',20]
        //     );//array('数据库字段名字'，'自定义的列名','设置该列的宽度')
        // $expTitle = "报表名称";
        // exportExcel_two($expTableData,$expCellName,$expTitle);

        // // csv导出
        // $headList = ['标题','作者','状态',"创建时间","发布时间"];
        // $fileName = "测试报表";
        // $exportUrl = 'php://output';
        // toExcel($list, $headList, $fileName, $exportUrl);


    }

    public function daoru(){
        // if($this->request->isPost()){
        //     // $this->request->file('file_excel')  上传的文件资源
        //     $arr = daoru_data($this->request->file('file_excel'));//返回的是个二维数组，然后后面写个方法插入到数据库就ok
        //     dump($arr);
        // }
           
        if($this->request->isPost()){
            $file = $this->request->file('file_excel');
            $info = $file->validate(['size'=>15678000,'ext'=>'xlsx,xls,csv'])->move('./public/upload');
            if ($info) {
                $extension = $info->getExtension();//获取文件后缀
                $exclePath = $info->getSaveName();  //获取文件名
                $file_name = './public/upload/' . $exclePath;   //上传文件的地址
                $hang_tit = ['A'=>'title','B'=>'author','C'=>'status','D'=>'create_time','E'=>'publish_time'];
                $arr = Import($file_name,3,$hang_tit);
                dump($arr);
            }
        }
    }
}