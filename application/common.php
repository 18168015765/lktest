<?php

use think\Db;

/**
 * 获取分类所有子分类
 * @param int $cid 分类ID
 * @return array|bool
 */
function get_category_children($cid)
{
    if (empty($cid)) {
        return false;
    }

    $children = Db::name('category')->where(['path' => ['like', "%,{$cid},%"]])->select();

    return array2tree($children);
}

/**
 * 根据分类ID获取文章列表（包括子分类）
 * @param int   $cid   分类ID
 * @param int   $limit 显示条数
 * @param array $where 查询条件
 * @param array $order 排序
 * @param array $filed 查询字段
 * @return bool|false|PDOStatement|string|\think\Collection
 */
function get_articles_by_cid($cid, $limit = 10, $where = [], $order = [], $filed = [])
{
    if (empty($cid)) {
        return false;
    }

    $ids = Db::name('category')->where(['path' => ['like', "%,{$cid},%"]])->column('id');
    $ids = (!empty($ids) && is_array($ids)) ? implode(',', $ids) . ',' . $cid : $cid;

    $fileds = array_merge(['id', 'cid', 'title', 'introduction', 'thumb', 'reading', 'publish_time'], (array)$filed);
    $map    = array_merge(['cid' => ['IN', $ids], 'status' => 1, 'publish_time' => ['<= time', date('Y-m-d H:i:s')]], (array)$where);
    $sort   = array_merge(['is_top' => 'DESC', 'sort' => 'DESC', 'publish_time' => 'DESC'], (array)$order);

    $article_list = Db::name('article')->where($map)->field($fileds)->order($sort)->limit($limit)->select();

    return $article_list;
}

/**
 * 根据分类ID获取文章列表，带分页（包括子分类）
 * @param int   $cid       分类ID
 * @param int   $page_size 每页显示条数
 * @param array $where     查询条件
 * @param array $order     排序
 * @param array $filed     查询字段
 * @return bool|\think\paginator\Collection
 */
function get_articles_by_cid_paged($cid, $page_size = 15, $where = [], $order = [], $filed = [])
{
    if (empty($cid)) {
        return false;
    }

    $ids = Db::name('category')->where(['path' => ['like', "%,{$cid},%"]])->column('id');
    $ids = (!empty($ids) && is_array($ids)) ? implode(',', $ids) . ',' . $cid : $cid;

    $fileds = array_merge(['id', 'cid', 'title', 'introduction', 'thumb', 'reading', 'publish_time'], (array)$filed);
    $map    = array_merge(['cid' => ['IN', $ids], 'status' => 1, 'publish_time' => ['<= time', date('Y-m-d H:i:s')]], (array)$where);
    $sort   = array_merge(['is_top' => 'DESC', 'sort' => 'DESC', 'publish_time' => 'DESC'], (array)$order);

    $article_list = Db::name('article')->where($map)->field($fileds)->order($sort)->paginate($page_size);

    return $article_list;
}

/**
 * 数组层级缩进转换
 * @param array $array 源数组
 * @param int   $pid
 * @param int   $level
 * @return array
 */
function array2level($array, $pid = 0, $level = 1)
{
    static $list = [];
    foreach ($array as $v) {
        if ($v['pid'] == $pid) {
            $v['level'] = $level;
            $list[]     = $v;
            array2level($array, $v['id'], $level + 1);
        }
    }

    return $list;
}

/**
 * 构建层级（树状）数组
 * @param array  $array          要进行处理的一维数组，经过该函数处理后，该数组自动转为树状数组
 * @param string $pid_name       父级ID的字段名
 * @param string $child_key_name 子元素键名
 * @return array|bool
 */
function array2tree(&$array, $pid_name = 'pid', $child_key_name = 'children')
{
    $counter = array_children_count($array, $pid_name);
    if (!isset($counter[0]) || $counter[0] == 0) {
        return $array;
    }
    $tree = [];
    while (isset($counter[0]) && $counter[0] > 0) {
        $temp = array_shift($array);
        if (isset($counter[$temp['id']]) && $counter[$temp['id']] > 0) {
            array_push($array, $temp);
        } else {
            if ($temp[$pid_name] == 0) {
                $tree[] = $temp;
            } else {
                $array = array_child_append($array, $temp[$pid_name], $temp, $child_key_name);
            }
        }
        $counter = array_children_count($array, $pid_name);
    }

    return $tree;
}

/**
 * 子元素计数器
 * @param array $array
 * @param int   $pid
 * @return array
 */
function array_children_count($array, $pid)
{
    $counter = [];
    foreach ($array as $item) {
        $count = isset($counter[$item[$pid]]) ? $counter[$item[$pid]] : 0;
        $count++;
        $counter[$item[$pid]] = $count;
    }

    return $counter;
}

/**
 * 把元素插入到对应的父元素$child_key_name字段
 * @param        $parent
 * @param        $pid
 * @param        $child
 * @param string $child_key_name 子元素键名
 * @return mixed
 */
function array_child_append($parent, $pid, $child, $child_key_name)
{
    foreach ($parent as &$item) {
        if ($item['id'] == $pid) {
            if (!isset($item[$child_key_name]))
                $item[$child_key_name] = [];
            $item[$child_key_name][] = $child;
        }
    }

    return $parent;
}

/**
 * 循环删除目录和文件
 * @param string $dir_name
 * @return bool
 */
function delete_dir_file($dir_name)
{
    $result = false;
    if (is_dir($dir_name)) {
        if ($handle = opendir($dir_name)) {
            while (false !== ($item = readdir($handle))) {
                if ($item != '.' && $item != '..') {
                    if (is_dir($dir_name . DS . $item)) {
                        delete_dir_file($dir_name . DS . $item);
                    } else {
                        unlink($dir_name . DS . $item);
                    }
                }
            }
            closedir($handle);
            if (rmdir($dir_name)) {
                $result = true;
            }
        }
    }

    return $result;
}

/**
 * 判断是否为手机访问
 * @return  boolean
 */
function is_mobile()
{
    static $is_mobile;

    if (isset($is_mobile)) {
        return $is_mobile;
    }

    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        $is_mobile = false;
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false
    ) {
        $is_mobile = true;
    } else {
        $is_mobile = false;
    }

    return $is_mobile;
}

/**
 * 手机号格式检查
 * @param string $mobile
 * @return bool
 */
function check_mobile_number($mobile)
{
    if (!is_numeric($mobile)) {
        return false;
    }
    $reg = '#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#';

    return preg_match($reg, $mobile) ? true : false;
}

function check_key_word($id,$name){
    $list = Db::name('category')->find($id);
    return $list[$name];
}

function check_key_word1($id,$name){
    $list = Db::name('article')->find($id);
    return $list[$name];
}

/**
 * 亲测可用
 * 导入excel数据到数据库
 * $file 需要导入的excel文件,是文件上传的资源  $this->request->file('file_excel')   file_excel是input里面的name的值
 */
function daoru_data($file){
    $objPHPExcel = new \PHPExcel();

    $info = $file->validate(['size'=>15678000,'ext'=>'xlsx,xls,csv'])->move('./public/upload');

    if($info){
        $extension = $info->getExtension();//获取文件后缀
        $exclePath = $info->getSaveName();  //获取文件名
        $file_name = './public/upload/' . $exclePath;   //上传文件的地址
        // $objReader =\PHPExcel_IOFactory::createReader('Excel5');
        // $obj_PHPExcel =$objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
        // echo "<pre>";

        if ($extension =='xlsx') {
            $objReader = new \PHPExcel_Reader_Excel2007();
            $obj_PHPExcel = $objReader ->load($file_name, $encode = 'utf-8');
        } else if ($extension =='xls') {
            $objReader = new \PHPExcel_Reader_Excel5();
            $obj_PHPExcel = $objReader ->load($file_name, $encode = 'utf-8');
        } else if ($extension=='csv') {
            $PHPReader = new \PHPExcel_Reader_CSV();

            //默认输入字符集
            $PHPReader->setInputEncoding('GBK');

            //默认的分隔符
            $PHPReader->setDelimiter(',');

            //载入文件
            $obj_PHPExcel = $PHPReader->load($file_name, $encode = 'utf-8');
        }

        $excel_array=$obj_PHPExcel->getsheet(0)->toArray();   //转换为数组格式
        array_shift($excel_array);  //删除第一个数组(标题);

        return $excel_array;
    }else{

        return false;
    }
}

/**
 * 导出excel  php5.6-php7以上都可用,此方法excel表的第一行是抬头的标题,第二行就是数据库数据了
 * @param array $data 导入数据
 * @param string $savefile 导出的excel文件名
 * @param array $fileheader excel的表头
 * @param string $sheetname sheet的标题名
 * @param array $widthArr 某几列的宽度 ['A'=>20,'B'=>30,'C'=>50]
 * @param $arr_hb    array  （可选）是否合并单元格 参数：['B' => 'author', 'c' => 'status']    'B' => 'author'表示B列的author字段值又重复的就纵向合并成一个
 */
function exportExcel_one($data, $savefile, $fileheader, $sheetname,$widthArr = [],$arr_hb = []){
    //引入phpexcel核心文件，不是tp，你也可以用include（‘文件路径’）来引入
    // Loader::import('PHPExcel', EXTEND_PATH);
    //Loader::import('PHPExcel.Reader.Excel2007');
    //或者excel5，用户输出.xls，不过貌似有bug，生成的excel有点问题，底部是空白，不过不影响查看。
    //import("Org.Util.PHPExcel.Reader.Excel5");
    //new一个PHPExcel类，或者说创建一个excel，tp中“\”不能掉
    $excel = new \PHPExcel();
    if (is_null($savefile)) {
        $savefile = time();
    }else{
        //防止中文命名，下载时ie9及其他情况下的文件名称乱码
        iconv('UTF-8', 'GB2312', $savefile);
    }
    //设置excel属性
    $objActSheet = $excel->getActiveSheet();
    //根据有生成的excel多少列，$letter长度要大于等于这个值
    // $a_z =  array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');//下面长的如果不行，就用这个短的
    $a_z =  array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

    $letter = array();
    for($i=0;$i<15;$i++){
        for($j=0;$j<26;$j++){
            $letter[] = $a_z[$i].$a_z[$j];
        }
    }
    $letter = array_merge($a_z,$letter);
    //设置当前的sheet
    $excel->setActiveSheetIndex(0);
    //设置sheet的name
    $objActSheet->setTitle($sheetname);
    //设置表头
    for($i = 0;$i < count($fileheader);$i++) {
        //单元宽度自适应,1.8.1版本phpexcel中文支持勉强可以，自适应后单独设置宽度无效
        //$objActSheet->getColumnDimension("$letter[$i]")->setAutoSize(true);
        //设置表头换行
        $excel->setActiveSheetIndex(0)->getStyle($letter[$i])->getAlignment()->setWrapText(true);
        //设置表头值，这里的setCellValue第二个参数不能使用iconv，否则excel中显示false
        $objActSheet->setCellValue("$letter[$i]1",$fileheader[$i]);
        //设置表头字体样式
        $objActSheet->getStyle("$letter[$i]1")->getFont()->setName('微软雅黑');
        //设置表头字体大小
        $objActSheet->getStyle("$letter[$i]1")->getFont()->setSize(12);
        //设置表头字体是否加粗
        $objActSheet->getStyle("$letter[$i]1")->getFont()->setBold(true);
        //设置表头文字垂直居中
        $objActSheet->getStyle("$letter[$i]1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置文字上下居中
        $objActSheet->getStyle($letter[$i])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置表头外的文字垂直居中
        $excel->setActiveSheetIndex(0)->getStyle($letter[$i])->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置表头每列的宽度
        $objActSheet->getColumnDimension($letter[$i])->setWidth(30);
    }
    // //单独设置D列宽度为15
    // $objActSheet->getColumnDimension('D')->setWidth(15);
    // //单独设置A列宽度为80
    // $objActSheet->getColumnDimension('A')->setWidth(80);

    //设置某一组列的对应的宽度
    if(!empty($widthArr)){
        foreach($widthArr as $k=>$v){
            $objActSheet->getColumnDimension($k)->setWidth($v);
        }
    }

    //这里$i初始值设置为2，$j初始值设置为0，自己体会原因 这个地方的值，要个合并单元格的地方的$s一样
    for ($i = 2;$i <= count($data) + 1;$i++) {
        $j = 0;
        foreach ($data[$i - 2] as $key=>$value) {
            $objActSheet->setCellValue("$letter[$j]$i",$value);
            $j++;
        }
        //设置单元格高度，暂时没有找到统一设置高度方法
        $objActSheet->getRowDimension($i)->setRowHeight('40px');
    }

    //是否合并单元格
    if (!empty($arr_hb) && !empty($data)) {
        foreach ($arr_hb as $k1=> $v1) {
            $letr = [];
            $year = $data[0][$v1];
            $s = 2;//控制从第几行开始是正式数据,2表示excel表格的第二行就是正式的数据,如果换成3的话，第一条数据就会缺失
            $e = 1;
            foreach ($data as $k=> $v) {
                if ($v[$v1] != $year) {
                    $letr[]= "$k1" . $s. ":$k1" . $e. "";
                    $e++;
                    $year= $v[$v1];
                    $s= $e;
                }else {
                    $e++;
                    if (count($data)== ($k+ 1)) {
                        $letr[]= "$k1" . $s. ":$k1" . $e. "";
                    }
                }
            }

            foreach ($letr as $aa) {
                $excel->getActiveSheet()->mergeCells($aa);
                $excel->getActiveSheet()->getstyle($aa)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//合并后的单元格文字垂直居中
            }
        }
    }

    header('Content-Type: application/vnd.ms-excel');
    //下载的excel文件名称，为Excel5，后缀为xls，不过影响似乎不大
    header('Content-Disposition: attachment;filename="' . $savefile . '.xlsx"');
    header('Cache-Control: max-age=0');
    // 用户下载excel
    $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $objWriter->save('php://output');
    // 保存excel在服务器上
    //$objWriter = new PHPExcel_Writer_Excel2007($excel);
    //或者$objWriter = new PHPExcel_Writer_Excel5($excel);
    //$objWriter->save("保存的文件地址/".$savefile);
}

/**
 * 公用的导出excel
 * php7及以上无法使用
 * @param string $expTitle       'XXXX表'
 * @param array  $expCellNamee  array('数据库字段名字'，'自定义的列名','设置该列的宽度')
 * @param array  $expTableData  array(0=>['id'=>1,'name'=>2]) 一般数据库读出的格式直接能用
*/
function exportExcel_two($expTableData,$expCellName,$expTitle){
    $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
    $fileName = $expTitle.date('_Ymd_His');//or $xlsTitle 文件名称可根据自己情况设定
    $cellNum = count($expCellName);
    $dataNum = count($expTableData);
    //vendor("PHPExcel.PHPExcel");
    $objPHPExcel = new \PHPExcel();

    //设置excel属性
    $objActSheet = $objPHPExcel->getActiveSheet();

    $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
    // $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));//第一行标题
    for($i=0;$i<$cellNum;$i++){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'1', $expCellName[$i][1]);
        $objPHPExcel->getActiveSheet()->getColumnDimension($cellName[$i])->setWidth($expCellName[$i][2]); //设置宽度

        //设置表头字体样式
        $objActSheet->getStyle($cellName[$i].'1')->getFont()->setName('微软雅黑');
        //设置表头字体大小
        $objActSheet->getStyle($cellName[$i].'1')->getFont()->setSize(12);
        //设置表头字体是否加粗
        $objActSheet->getStyle($cellName[$i].'1')->getFont()->setBold(true);
        //设置表头文字垂直居中
        $objActSheet->getStyle($cellName[$i].'1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //设置文字上下居中
        $objActSheet->getStyle($cellName[$i])->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置表头外的文字垂直居中
        $objPHPExcel->setActiveSheetIndex(0)->getStyle($cellName[$i])->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    }
    // Miscellaneous glyphs, UTF-8
    for($i=0;$i<$dataNum;$i++){
        for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), $expTableData[$i][$expCellName[$j][0]]);
        }
    }
    header('pragma:public');
    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
    header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

/**
 * csv导出Excel数据表格
 * @param  array    $dataList     要导出的数组格式的数据
 * @param  array    $headList     导出的Excel数据第一列表头
 * @param  string   $fileName     输出Excel表格文件名
 * @param  string   $exportUrl    直接输出到浏览器or输出到指定路径文件下   (注意点：
        参数：$exportUrl分两种情况，根据需求选择其一
        1.$exportUrl = 'php://output' 表示表示直接输出到浏览器自动下载。
        2.exportUrl="服务器目录地址/文件名.csv"表示输出到指定路径文件下。举例：exportUrl = "/data/a.csv")
 * @return bool|false|string
 * 示例
        $headList = ['id', '姓名', '年龄'];
        $dataList = [[1, 'zhangsan', 10], [2, 'lisi', 20]];
        toExcel($dataList, $headList, '测试文件名称', 'php://output');
 */
function toExcel($dataList,$headList,$fileName,$exportUrl){
    //set_time_limit(0);//防止超时
    //ini_set("memory_limit", "512M");//防止内存溢出
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$fileName.'.csv"');
    header('Cache-Control: max-age=0');
    //打开PHP文件句柄,php://output 表示直接输出到浏览器,$exportUrl表示输出到指定路径文件下
    $fp = fopen($exportUrl, 'a');

    //输出Excel列名信息
    foreach ($headList as $key => $value) {
        //CSV的Excel支持GBK编码，一定要转换，否则乱码
        $headList[$key] = iconv('utf-8', 'gbk', $value);
    }

    //将数据通过fputcsv写到文件句柄
    fputcsv($fp, $headList);

    //计数器
    $num = 0;

    //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
    $limit = 100000;

    //逐行取出数据，不浪费内存
    $count = count($dataList);
    for ($i = 0; $i < $count; $i++) {

        $num++;

        //刷新一下输出buffer，防止由于数据过多造成问题
        if ($limit == $num) {
            ob_flush();
            flush();
            $num = 0;
        }

        $row = $dataList[$i];
        foreach ($row as $key => $value) {
            $row[$key] = iconv('utf-8', 'gbk', $value);
        }
        fputcsv($fp, $row);
    }
    return $fileName;
}




















































/**
    * php5.6可用,php7以上无法使用
    * @param $data  array      （必须）数据（二维数组）【只能存在要导出的数据】
    * @param $title  array    （必须）列标中文 ['标题1', '标题1', '标题1', '标题1', '标题1', '标题1']
    * @param $filename string    （必须）文件名称
    * @param string $sheetname sheet的标题名
    * @param $cellNames array  （必须）列标英文 ['A', 'B', 'C', 'D', 'E', 'F']
    * @param $arr_hb    array  （可选）是否合并单元格 参数：['B' => 'author', 'c' => 'status']    'B' => 'author'表示B列的author字段值又重复的就纵向合并成一个
    * @param array $widthArr 某几列的宽度 ['A'=>20,'B'=>30,'C'=>50]
    * @throws \PHPExcel_Exception
*/
function Export($data, $title, $filename, $sheetname, $cellNames, $arr_hb=[], $widthArr=[])
{
    $objPHPExcel = new \PHPExcel();
    $cellArr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'];
    $cellName= [];
    foreach($cellNames as $key=>$val){
        foreach($cellArr as $k=>$v){
            $cellName[]= $val. $v;
        }
    }
    $cellName= array_merge($cellArr, $cellName);
    /* 设置宽度 */
    // $objPHPExcel->getActiveSheet()->getColumnDimension()->setAutoSize(true);
    $objPHPExcel->getActiveSheet(0)->mergeCells('A1:AC1');//合并单元格（第一行标题的单元格）
    $objPHPExcel->getactivesheet()->setCellValue('A1', $filename); //设置标题
    //设置SHEET
    $objPHPExcel->setactivesheetindex(0);
    $objPHPExcel->getActiveSheet()->setTitle($sheetname);
    $_row= 2;//设置纵向单元格标识
    foreach ($title as $k=> $v) {
        $objPHPExcel->getactivesheet()->setCellValue($cellName[$k]. $_row, $v);
    }

    //设置某一组列的对应的宽度
    if(!empty($widthArr)){
        foreach($widthArr as $k=>$v){
            $objPHPExcel->getActiveSheet()->getColumnDimension($k)->setWidth($v);
        }
    }

    $i= 1;
    foreach ($data AS $_v) {
        $j= 0;
        foreach ($_v as $_cell) {
            if ($cellName[$j]== 'A' || $cellName[$j]== 'F' || $cellName[$j]== 'L') {//科学转换
                $objPHPExcel->getActiveSheet()->setCellValue($cellName[$j]. ($i+ $_row), "\t" . $_cell. "\t");
            }else {
                $objPHPExcel->getActiveSheet()->setCellValue($cellName[$j]. ($i+ $_row), $_cell);
            }
            $j++;
        }
        $i++;
    }
    //是否合并单元格
    if (!empty($arr_hb) && !empty($data)) {
        foreach ($arr_hb as $k1=> $v1) {
            $lert= _remerge($data, $k1, $v1);
            foreach ($lert as $aa) {
                $objPHPExcel->getActiveSheet()->mergeCells($aa);
                $objPHPExcel->getActiveSheet()->getstyle($aa)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//合并后的单元格文字垂直居中
            }
        }
    }
    //输出到浏览器
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
    header("Content-Type:application/force-download");
    header("Content-Type:application/vnd.ms-execl");
    header("Content-Type:application/octet-stream");
    header("Content-Type:application/download");
    header('Content-Disposition:attachment;filename="' . $filename . '.xls"');
    header("Content-Transfer-Encoding:binary");
    // version_compare(PHP_VERSION,'5.6')
    $objWriter= \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    // $objWriter= \PHPExcel_IOFactory::createWriter($objPHPExcel, '2007');
    $objWriter->save('php://output');
}

function _remerge($arr, $let, $field)
{
    $letr = [];
    $year = $arr[0][$field];
    $s = 3;
    $e = 2;
    foreach ($arr as $k=> $v) {
        if ($v[$field] != $year) {
            $letr[]= "$let" . $s. ":$let" . $e. "";
            $e++;
            $year= $v[$field];
            $s= $e;
        }else {
            $e++;
            if (count($arr)== ($k+ 1)) {
                $letr[]= "$let" . $s. ":$let" . $e. "";
            }
        }
    }
    return $letr;
}

/**
* @param string $filename  要导入的文件
* @param int $start_get    从那一行开始读取
* @param array $hang_tit  头部(其实就是要插入到数据的字段名)  ['A'=>'title','B'=>'author','C'=>'status','D'=>'create_time','E'=>'publish_time']
* @return array|string
* @throws \PHPExcel_Exception
* @throws \PHPExcel_Reader_Exception
*/
function Import($file,$start_get=3,$hang_tit=[]){
    if (!file_exists($file)) {
        return '文件不存在';
    }
    $objPHPExcel = new \PHPExcel();
    $objPHPExcel= \PHPExcel_IOFactory::load($file); //自动文件类型 无需自定义
    $sheet= $objPHPExcel->getSheet(0);
    $highestRow= $sheet->getHighestRow(); // 取得总行数
    $index= 0;
    $hang_tit = empty($hang_tit)?['A'=>'name','B'=>'title','C'=>'phone','D'=>'sex']:$hang_tit;
    $list= [];
    for($start_get;$start_get<=$highestRow;$start_get++)
    {
        foreach ($hang_tit as $k=>$v){
            $list[$index][$v]= trim($objPHPExcel->getActiveSheet()->getCell($k.$start_get)->getValue());//获取A列的值
        }
        $index++;
    }
    return $list;
}


/**
 * 生成宣传海报   需要开启PHP的GD扩展
 * @param array  参数,包括图片和文字
 * @param string  $filename 生成海报文件名,不传此参数则不生成文件,直接输出图片
 * @return [type] [description]
 *
 使用示例一：生成带有二维码的海报
 $config = array(
  'image'=>array(
    array(
      'url'=>'ewm.png',     //二维码资源
      'stream'=>0,
      'left'=>360,
      'top'=>255,
      'right'=>0,
      'bottom'=>0,
      'width'=>100,
      'height'=>100,
      'opacity'=>100
    )
  ),
  'background'=>'psu.jpg'          //背景图
);

使用示例二：生成带有图像，昵称和二维码的海报
$config = array(
  'text'=>array(
    array(
      'text'=>'昵称',
      'left'=>182,
      'top'=>105,
      'fontPath'=>'qrcode/simhei.ttf',     //字体文件
      'fontSize'=>18,             //字号
      'fontColor'=>'255,0,0',       //字体颜色
      'angle'=>0,
    )
  ),
  'image'=>array(
    array(
      'url'=>'qrcode/qrcode.png',       //图片资源路径
      'left'=>130,
      'top'=>-140,
      'stream'=>0,             //图片资源是否是字符串图像流
      'right'=>0,
      'bottom'=>0,
      'width'=>150,
      'height'=>150,
      'opacity'=>100
    ),
    array(
      'url'=>'https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eofD96opK97RXwM179G9IJytIgqXod8jH9icFf6Cia6sJ0fxeILLMLf0dVviaF3SnibxtrFaVO3c8Ria2w/0',//昵称对应的头像
      'left'=>120,
      'top'=>70,
      'right'=>0,
      'stream'=>0,
      'bottom'=>0,
      'width'=>55,
      'height'=>55,
      'opacity'=>100
    ),
  ),
  'background'=>'qrcode/bjim.jpg',//背景图
);

调用实例
$filename = time().'.jpg';
// echo createPoster($config,$filename);//生成图片到本地的文件夹里
echo createPoster($config);//在浏览器上显示
 */
function createPoster($config=array(),$filename=""){
  //如果要看报什么错，可以先注释调这个header
  if(empty($filename)) header("content-type: image/png");
  $imageDefault = array(
    'left'=>0,
    'top'=>0,
    'right'=>0,
    'bottom'=>0,
    'width'=>100,
    'height'=>100,
    'opacity'=>100
  );
  $textDefault = array(
    'text'=>'',
    'left'=>0,
    'top'=>0,
    'fontSize'=>32,       //字号
    'fontColor'=>'255,255,255', //字体颜色
    'angle'=>0,
  );
  $background = $config['background'];//海报最底层得背景
  //背景方法
  $backgroundInfo = getimagesize($background);
  $backgroundFun = 'imagecreatefrom'.image_type_to_extension($backgroundInfo[2], false);
  $background = $backgroundFun($background);
  $backgroundWidth = imagesx($background);  //背景宽度
  $backgroundHeight = imagesy($background);  //背景高度
  $imageRes = imageCreatetruecolor($backgroundWidth,$backgroundHeight);
  $color = imagecolorallocate($imageRes, 0, 0, 0);
  imagefill($imageRes, 0, 0, $color);
  // imageColorTransparent($imageRes, $color);  //颜色透明
  imagecopyresampled($imageRes,$background,0,0,0,0,imagesx($background),imagesy($background),imagesx($background),imagesy($background));
  //处理了图片
  if(!empty($config['image'])){
    foreach ($config['image'] as $key => $val) {
      $val = array_merge($imageDefault,$val);
      $info = getimagesize($val['url']);
      $function = 'imagecreatefrom'.image_type_to_extension($info[2], false);
      if($val['stream']){   //如果传的是字符串图像流
        $info = getimagesizefromstring($val['url']);
        $function = 'imagecreatefromstring';
      }
      $res = $function($val['url']);
      $resWidth = $info[0];
      $resHeight = $info[1];
      //建立画板 ，缩放图片至指定尺寸
      $canvas=imagecreatetruecolor($val['width'], $val['height']);
      imagefill($canvas, 0, 0, $color);
      //关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
      imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'],$resWidth,$resHeight);
      $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']) - $val['width']:$val['left'];
      $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']) - $val['height']:$val['top'];
      //放置图像
      imagecopymerge($imageRes,$canvas, $val['left'],$val['top'],$val['right'],$val['bottom'],$val['width'],$val['height'],$val['opacity']);//左，上，右，下，宽度，高度，透明度
    }
  }
  //处理文字
  if(!empty($config['text'])){
    foreach ($config['text'] as $key => $val) {
      $val = array_merge($textDefault,$val);
      list($R,$G,$B) = explode(',', $val['fontColor']);
      $fontColor = imagecolorallocate($imageRes, $R, $G, $B);
      $val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']):$val['left'];
      $val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']):$val['top'];
      imagettftext($imageRes,$val['fontSize'],$val['angle'],$val['left'],$val['top'],$fontColor,$val['fontPath'],$val['text']);
    }
  }
  //生成图片
  if(!empty($filename)){
    $res = imagejpeg ($imageRes,$filename,90); //保存到本地
    imagedestroy($imageRes);
    if(!$res) return false;
    return $filename;
  }else{
    imagejpeg ($imageRes);     //在浏览器上显示
    imagedestroy($imageRes);
  }
}













