<?php

/**
 * 小滨汽配 管理中心供货商管理
 * ============================================================================
 * 版权所有 2015-2016 小滨汽配科技有限公司，并保留所有权利。
 * 网站地址: www.xiaobinqipei.com；
 * ----------------------------------------------------------------------------
 * 仅供学习交流使用，如需商用请购买正版版权。小滨汽配不承担任何法律责任。
 * 踏踏实实做事，堂堂正正做人。
 * ============================================================================
 * $Author: 68ecshop $
 * $Id: depts.php 15013 2009-05-13 09:31:42Z 68ecshop $
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
//require(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/dept.php');
$smarty->assign('lang', $_LANG);


/*------------------------------------------------------ */
//-- 供货商列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
     /* 检查权限 */
     admin_priv('dept_manage');

    /* 查询 */
    $result = depts_list();

    /* 模板赋值 */
	$ur_here_lang = $_REQUEST['status'] =='1' ? $_LANG['dept_list'] : $_LANG['dept_reg_list'];
    $smarty->assign('ur_here', $ur_here_lang); // 当前导航

    $smarty->assign('full_page',        1); // 翻页参数

    $smarty->assign('status',    $_REQUEST['status']);
    $smarty->assign('dept_list',    $result['result']);
    $smarty->assign('filter',       $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count',   $result['page_count']);
    $smarty->assign('sort_depts_id', '<img src="images/sort_desc.gif">');

    /* 显示模板 */
    assign_query_info();
    $smarty->display('dept_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    check_authz_json('dept_manage');

    $result = depts_list();

    $smarty->assign('dept_list',    $result['result']);
    $smarty->assign('filter',       $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count',   $result['page_count']);

    /* 排序标记 */
    $sort_flag  = sort_flag($result['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('dept_list.htm'), '',
        array('filter' => $result['filter'], 'page_count' => $result['page_count']));
}


/*------------------------------------------------------ */
//-- 查看、编辑供货商
/*------------------------------------------------------ */
elseif ($_REQUEST['act']== 'edit')
{
    /* 检查权限 */
    admin_priv('dept_manage');
    $depts = array();

     /* 取得供货商信息 */
     $id = $_REQUEST['id'];
// 	 $status = intval($_REQUEST['status']);
     $sql = "SELECT * FROM " . $ecs->table('dept') . " WHERE dept_id = '$id'";
     $dept = $db->getRow($sql);
     if (count($dept) <= 0)
     {
          sys_msg('该仓库不存在！');
     }

     $user_info = $db->getRow('SELECT * FROM ' . $ecs->table('users') . ' where user_id = ' . $dept['user_id']);
     $dept['user_name'] = $user_info['user_name'];
     
	/* 省市县 */
	$dept_country = $dept['country'] ?  $dept['country'] : $_CFG['shop_country'];
	$smarty->assign('country_list',       get_regions());	
	$smarty->assign('province_list', get_regions(1, $dept_country));
	$smarty->assign('city_list', get_regions(2, $dept['province']));
	$smarty->assign('district_list', get_regions(3, $dept['city']));
	$smarty->assign('dept_country', $dept_country);

    $smarty->assign('ur_here', $_LANG['edit_dept']);
    $smarty->assign('action_link', array('href' => 'dept.php?act=list&status=1', 'text' =>'仓库列表' ));

    $smarty->assign('form_action', 'update');
    $smarty->assign('dept', $dept);

    //用户列表
    $ex_where = ' where is_validated = 1';
    $sql = "SELECT user_id, user_name, email, mobile_phone, is_validated, validated, user_money, frozen_money, rank_points, pay_points, status, reg_time, froms "." FROM " . $GLOBALS['ecs']->table('users') . $ex_where . " ORDER by reg_time LIMIT 500";
	$user_list = $GLOBALS['db']->getAll($sql);
    //dump($user_list);die;
	$smarty->assign('user_list', $user_list);

     assign_query_info();

     $smarty->display('dept_info.htm');
   

}

/*------------------------------------------------------ */
//-- 查看供货商佣金日志
/*------------------------------------------------------ */
elseif ($_REQUEST['act']== 'view')
{
	/* 检查权限 */
    admin_priv('dept_manage');

	/* 查询 */
    $result = rebate_log_list();

    /* 模板赋值 */
    $smarty->assign('ur_here', '佣金日志记录'); // 当前导航

    $smarty->assign('full_page',        1); // 翻页参数

    $smarty->assign('log_list',    $result['result']);
    $smarty->assign('filter',       $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count',   $result['page_count']);
    $smarty->assign('sort_depts_id', '<img src="images/sort_desc.gif">');

    /* 显示模板 */
    assign_query_info();
    $smarty->display('dept_log_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query_log')
{
    check_authz_json('dept_manage');

    $result = rebate_log_list();

    $smarty->assign('log_list',    $result['result']);
    $smarty->assign('filter',       $result['filter']);
    $smarty->assign('record_count', $result['record_count']);
    $smarty->assign('page_count',   $result['page_count']);
;

    make_json_result($smarty->fetch('dept_log_list.htm'), '',
        array('filter' => $result['filter'], 'page_count' => $result['page_count']));
}

/*------------------------------------------------------ */
//-- 提交添加、编辑供货商
/*------------------------------------------------------ */
elseif ($_REQUEST['act']== 'update')
{
    /* 检查权限 */
    admin_priv('dept_manage');   

    //必须要填写的项目
    if(!$_POST['dept_name']){
        sys_msg('请填写仓库名称！');
    }

    if(!intval($_POST['manage_user_id'])){
        sys_msg('请选择管理员！');
    }


    //获取管理员信息
    $ex_where = ' where user_id = ' . intval($_POST['manage_user_id']);
    $sql = "SELECT user_id, user_name, email, mobile_phone, is_validated, validated, user_money, frozen_money, rank_points, pay_points, status, reg_time, ec_salt "." FROM " . $GLOBALS['ecs']->table('users') . $ex_where;
	$user_info = $GLOBALS['db']->getRow($sql);
    if (!$user_info) {
        sys_msg('获取不到管理员信息！');
    }

    $dept_id = intval($_POST['id']);
    //介绍
    $dept_desc = trim($_POST['dept_desc']);
    $tel = trim($_POST['tel']);

    //查看此用户是否已是管理员
    $have_user = $GLOBALS['db']->getAll('SELECT * FROM ' . $GLOBALS['ecs']->table('dept_admin_user') . 'where uid = ' . intval($_POST['manage_user_id']) . ' AND dept_id != ' . $dept_id);
    if ($have_user) sys_msg('此用户已成为其他仓库管理员了, 请添加其他用户');

    //如果密码存在则修改，否则不处理
    if ($_POST['password']) {
        if (strlen($_POST['password']) < 6) {
            sys_msg('请填写密码, 且密码长度不得小于6位！');
        } elseif (md5($_POST['password']) != md5($_POST['confirm_password'])) {
            sys_msg('两次密码不一致！');
        }
    } else {
        sys_msg('请输入密码！');
    }

	//$username = empty($_POST['username']) ? '' : trim($_POST['username']);
	//$email = empty($_POST['email']) ? '' : trim($_POST['email']);
	//$real_name = empty($_POST['real_name']) ? '' : trim($_POST['real_name']);

	$password = empty($_POST['password']) ? '' : trim($_POST['password']);
	$country = $_POST['country'];
	$province = $_POST['province'];
	$city = $_POST['city'];
	$district = $_POST['district'];
	$address = empty($_POST['address']) ? '' : trim($_POST['address']);

	$dept_name =trim($_POST['dept_name']);
    $last_user_id = intval($_POST['last_user_id']);

    //密码加密码规则，如果没有ec_salt进行一次md5,反之进行两次md5
    if ($user_info['ec_salt']) {
        $md5pass = md5(md5($password) . $user_info['ec_salt']);
    } else {
        $md5pass = md5($password);
    }

    $re_sql = 'SELECT region_name FROM ' . $GLOBALS['ecs']->table('region') . ' where region_id = ';
    $province_name = $GLOBALS['db']->getOne($re_sql . $province);
    $city_name = $GLOBALS['db']->getOne($re_sql . $city);
    $area_name = $GLOBALS['db']->getOne($re_sql . $district);
    $all_address = $province_name . $city_name . $area_name . $address;

    $point = getPointByAddress($all_address);

    //dept
    $dept_field = array(
        'dept_name' => $dept_name,
        'country' => $country,
        'province' => $province,
        'city' => $city,
        'district' => $district,
        'address' => $address,
        'tel'     => $tel,
        'email'    => $user_info['email'],
        'dept_remark' => $dept_desc,
        'lng' => $point['lng'],
        'lat' => $point['lat'],
        'user_id' => $user_info['user_id']
    );
	$db->autoExecute($ecs->table('dept'), $dept_field, 'UPDATE', "dept_id = '" . $dept_id . "'");

    //depts
    $depts_field = array(
        'depts_name' => $dept_name,
        'depts_desc' => $dept_desc,
    );
	$db->autoExecute($ecs->table('depts'), $depts_field, 'UPDATE', "depts_id = '" . $dept_id . "'");

    //入dept_admin_user
    $db->query('DELETE FROM ' .  $ecs->table('dept_admin_user') . ' where uid = ' . $last_user_id);

    $insql = "INSERT INTO " . $ecs->table('dept_admin_user') . " (`uid`, `user_name`, `email`, `password`, `ec_salt`, `add_time`, `last_login`, `last_ip`, `action_list`, `nav_list`, `lang_type`, `agency_id`, `dept_id`, `todolist`, `role_id`, `checked`) ".  "VALUES(".$user_info['user_id'].", '".$user_info['user_name']."', '".$user_info['email']."', '".$md5pass."', '".$user_info['ec_salt']."', ".strtotime('now').", '".$user_info['last_login']."', '".$user_info['last_ip']."', 'all', '', '', 0, ".$dept_id.", NULL, NULL, 1)";
    $db->query($insql);

	admin_log($_POST['dept_name'], 'edit', 'dept');
    
	 /* 清除缓存 */
	clear_cache_files();

	/* 提示信息 */
	$links[] = array('href' => ('dept.php?act=list'), 'text' => '修改成功');
	sys_msg('修改成功', 0, $links);    

}

//删除店铺信息
elseif ($_REQUEST['act'] == 'delete'){
	/* 检查权限 */
    admin_priv('dept_manage'); 
	$dept_id =  intval($_GET['id']);

	$sql = "SELECT * FROM " . $ecs->table('dept') . " WHERE dept_id = ".$dept_id;
    $dept = $db->getRow($sql);
    if (count($dept) <= 0)
    {
        sys_msg('该仓库不存在！');
    }


	if($dept_id > 0){
		$ret = array();
		//入驻商相关删除信息
		$dept_info = array(
			'delete FROM '.$ecs->table('dept_admin_user').' WHERE dept_id = '.$dept_id,
			'delete FROM '.$ecs->table('dept_article').' WHERE dept_id = '.$dept_id,
			'delete FROM '.$ecs->table('dept_category').' WHERE dept_id = '.$dept_id,
			'delete FROM '.$ecs->table('dept_cat_recommend').' WHERE dept_id = '.$dept_id,
			'delete FROM '.$ecs->table('dept_goods_cat').' WHERE dept_id = '.$dept_id,
			'delete FROM '.$ecs->table('dept_guanzhu').' WHERE deptid = '.$dept_id,
			'delete FROM '.$ecs->table('dept_money_log').' WHERE dept_id = '.$dept_id,
			'delete FROM '.$ecs->table('dept_nav').' WHERE dept_id = '.$dept_id,
			'delete FROM '.$ecs->table('dept_rebate_log').' WHERE rebateid in (SELECT rebate_id FROM '.$ecs->table('dept_rebate').' WHERE dept_id ='.$dept_id.')',
			'delete FROM '.$ecs->table('dept_shop_config').' WHERE dept_id = '.$dept_id,
			'delete FROM '.$ecs->table('dept_street').' WHERE dept_id = '.$dept_id,
			'delete FROM '.$ecs->table('dept_tag_map').' WHERE dept_id = '.$dept_id
		);
		foreach($dept_info as $sk=>$sv){
			if($db->query($sv)){}else{
				$ret[] = $sv;
			}
		}
		delete_dept_pic($dept_id);
		//商品相关删除信息
		$goods_info = array(
			'delete FROM '.$ecs->table('goods_activity').' WHERE goods_id in (SELECT goods_id FROM '.$ecs->table('goods').' WHERE dept_id ='.$dept_id.')',
			'delete FROM '.$ecs->table('goods_attr').' WHERE goods_id in (SELECT goods_id FROM '.$ecs->table('goods').' WHERE dept_id ='.$dept_id.')',
			'delete FROM '.$ecs->table('goods_cat').' WHERE goods_id in (SELECT goods_id FROM '.$ecs->table('goods').' WHERE dept_id ='.$dept_id.')',
			'delete FROM '.$ecs->table('goods_gallery').' WHERE goods_id in (SELECT goods_id FROM '.$ecs->table('goods').' WHERE dept_id ='.$dept_id.')',
			'delete FROM '.$ecs->table('goods_tag').' WHERE goods_id in (SELECT goods_id FROM '.$ecs->table('goods').' WHERE dept_id ='.$dept_id.')',
			'delete FROM '.$ecs->table('products').' WHERE goods_id in (SELECT goods_id FROM '.$ecs->table('goods').' WHERE dept_id ='.$dept_id.')'
		);
		foreach($goods_info as $gk=>$gv){
			if($db->query($gv)){}else{
				$ret[] = $gv;
			}
		}
		//最后删除中间表信息
		$other_info = array(
			'delete FROM '.$ecs->table('goods').' WHERE dept_id = '.$dept_id,
			'delete FROM '.$ecs->table('dept').' WHERE dept_id = '.$dept_id,
			'delete FROM '.$ecs->table('dept_rebate').' WHERE dept_id = '.$dept_id
		);
		foreach($other_info as $ok=>$ov){
			if($db->query($ov)){}else{
				$ret[] = $ov;
			}
		}
		
	}
	if(count($ret)>0){
		echo "如下删除语句执行失败:";
		echo "<pre>";
		print_r($ret);
		sleep(10);
	}

	/* 提示信息 */
	$links[0] = array('href' => 'dept.php?act=list&status='.$dept['status'], 'text' =>'返回上一页');
	sys_msg('删除成功!',0,$links);
} 
elseif ($_REQUEST['act'] == 'add') {
	// 全局变量
	$user = $GLOBALS['user'];
	$_CFG = $GLOBALS['_CFG'];
	$_LANG = $GLOBALS['_LANG'];
	$smarty = $GLOBALS['smarty'];
	$db = $GLOBALS['db'];
	$ecs = $GLOBALS['ecs'];
	$user_id = $_SESSION['user_id'];
	
	/* 检查权限 */
	admin_priv('dept_manage');
	
	$smarty->assign('action_link', array(
		'text' => $_LANG['03_dept_list'],'href' => 'dept.php?act=list'
	));
	$smarty->assign('form_action', 'insert');
	$smarty->assign('user', $user);
	
	$smarty->assign('lang', $_LANG);
	$smarty->assign('country_list', get_regions());
	$province_list = get_regions(1, $row['country']);
	$city_list = get_regions(2, $row['province']);
	$district_list = get_regions(3, $row['city']);

	$smarty->assign('province_list', $province_list);
	$smarty->assign('city_list', $city_list);
	$smarty->assign('district_list', $district_list);

    //用户列表
    $ex_where = ' where is_validated = 1';
    $sql = "SELECT user_id, user_name, email, mobile_phone, is_validated, validated, user_money, frozen_money, rank_points, pay_points, status, reg_time, froms "." FROM " . $GLOBALS['ecs']->table('users') . $ex_where . " ORDER by reg_time LIMIT 500";
	$user_list = $GLOBALS['db']->getAll($sql);
    //dump($user_list);die;
	$smarty->assign('user_list', $user_list);
	
	assign_query_info();
	$smarty->display('dept_info.htm');
}
/*注册仓库，先进用户表，->dept ->depts ->dept_admin_user */
elseif ($_REQUEST['act'] == 'insert') 
{
    //dump($_REQUEST);die;
    /* 检查权限 */
    admin_priv('dept_manage');   
    
    //必须要填写的项目
    if(!$_POST['dept_name']){
        sys_msg('请填写仓库名称！');
    }

    if(!intval($_POST['manage_user_id'])){
        sys_msg('请选择管理员！');
    }
    //if(is_mobile_phone($_POST['mobile_phone'])){
    //    sys_msg('请填写正确的手机号！');
    //}
    if (!$_POST['password'] || strlen($_POST['password']) < 6) {
        sys_msg('请填写密码, 且密码长度不得小于6位！');
    } elseif (md5($_POST['password']) != md5($_POST['confirm_password'])) {
        sys_msg('两次密码不一致！');
    }

	//$username = empty($_POST['username']) ? '' : trim($_POST['username']);
	//$email = empty($_POST['email']) ? '' : trim($_POST['email']);
	//$real_name = empty($_POST['real_name']) ? '' : trim($_POST['real_name']);
	$password = empty($_POST['password']) ? '' : trim($_POST['password']);
	$country = $_POST['country'];
	$province = $_POST['province'];
	$city = $_POST['city'];
	$district = $_POST['district'];
	$address = empty($_POST['address']) ? '' : trim($_POST['address']);

	$dept_name =trim($_POST['dept_name']);

    //获取管理员信息
    $ex_where = ' where user_id = ' . intval($_POST['manage_user_id']);
    $sql = "SELECT user_id, user_name, email, mobile_phone, is_validated, validated, user_money, frozen_money, rank_points, pay_points, status, reg_time, ec_salt "." FROM " . $GLOBALS['ecs']->table('users') . $ex_where;
	$user_info = $GLOBALS['db']->getRow($sql);
    if (!$user_info) {
        sys_msg('获取不到管理员信息！');
    }

    //查看此用户是否已是管理员
    $have_user = $GLOBALS['db']->getRow('SELECT * FROM ' . $GLOBALS['ecs']->table('dept_admin_user') . 'where uid = ' . intval($_POST['manage_user_id']));
    if ($have_user) sys_msg('此用户已成为仓库管理员了, 请添加其他用户');

    //密码加密码规则，如果没有ec_salt进行一次md5,反之进行两次md5
    if ($user_info['ec_salt']) {
        $md5pass = md5(md5($password) . $user_info['ec_salt']);
    } else {
        $md5pass = md5($password);
    }

    $re_sql = 'SELECT region_name FROM ' . $GLOBALS['ecs']->table('region') . ' where region_id = ';
    $province_name = $GLOBALS['db']->getOne($re_sql . $province);
    $city_name = $GLOBALS['db']->getOne($re_sql . $city);
    $area_name = $GLOBALS['db']->getOne($re_sql . $district);
    $all_address = $province_name . $city_name . $area_name;

    $point = getPointByAddress($all_address);

    $dept_desc = trim($_POST['dept_desc']);
    $tel = trim($_POST['tel']);

    //dept
    $dept_insql = "INSERT INTO " . $ecs->table('dept') . " (`user_id`,`dept_name`, `country`, `province`, `city`, `district`, `address`, `tel`, `email`, `dept_remark`, `status`, `add_time`, `lng`, `lat`) ".  "VALUES('"  .$user_info['user_id'] . "','".$dept_name."', ".$country.", ".$province.", ".$city.", ".$district.", '".$address."', '".$tel."', '".$user_info['email']."', '.".$dept_desc."', 1, ".strtotime('now').", ".$point['lng']. ",".$point['lat'] .")";
    $db->query($dept_insql);
    $dept_id = $db->insert_Id();
    if (!$dept_id) sys_msg('仓库表出错');

    //depts
    $depts_insql = 'INSERT INTO ' . $ecs->table('depts') . ' (`depts_id`, `depts_name`, `depts_desc`,`is_check`) VALUES("' . $dept_id . '", "' . $dept_name . '", "'.$dept_desc.'", 1)';
    $db->query($depts_insql);

    //入dept_admin_user
    $insql = "INSERT INTO " . $ecs->table('dept_admin_user') . " (`uid`, `user_name`, `email`, `password`, `ec_salt`, `add_time`, `last_login`, `last_ip`, `action_list`, `nav_list`, `lang_type`, `agency_id`, `dept_id`, `todolist`, `role_id`, `checked`) ".  "VALUES(".$user_info['user_id'].", '".$user_info['user_name']."', '".$user_info['email']."', '".$md5pass."', '".$user_info['ec_salt']."', ".strtotime('now').", '".$user_info['last_login']."', '".$user_info['last_ip']."', 'all', '', '', 0, ".$dept_id.", NULL, NULL, 1)";
    $db->query($insql);

	admin_log($_POST['dept_name'], 'add', 'dept');

	/* 提示信息 */
	$link[] = array(
		'text' => $_LANG['go_back'],'href' => 'dept.php?act=list'
	);
	sys_msg(sprintf('添加成功', htmlspecialchars(stripslashes($dept_name))), 0, $link);
}

/**
 *  获取仓库列表信息
 *
 * @access  public
 * @param
 *
 * @return void
 */
function depts_list()
{
    $result = get_filter();
    if ($result === false)
    {
        $aiax = isset($_GET['is_ajax']) ? $_GET['is_ajax'] : 0;

        /* 过滤信息 */
        $filter['dept_name'] = empty($_REQUEST['dept_name']) ? '' : trim($_REQUEST['dept_name']);
        $filter['rank_name'] = empty($_REQUEST['rank_name']) ? '' : trim($_REQUEST['rank_name']);
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'dept_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'ASC' : trim($_REQUEST['sort_order']);
		$filter['status'] = empty($_REQUEST['status']) ? '0' : intval($_REQUEST['status']);
       
        $where = 'WHERE 1 ';
		$where .= $filter['status'] ? " AND s.status = '". $filter['status']. "' " : " AND s.status =1 ";
        if ($filter['dept_name'])
        {
            $where .= " AND dept_name LIKE '%" . mysql_like_quote($filter['dept_name']) . "%'";
        }
		if ($filter['rank_name'])
        {
            $where .= " AND rank_id = '$filter[rank_name]'";
        }

        /* 分页大小 */
        $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

        if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0)
        {
            $filter['page_size'] = intval($_REQUEST['page_size']);
        }
        elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0)
        {
            $filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
        }
        else
        {
            $filter['page_size'] = 15;
        }

        /* 记录总数 */
        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('dept') ." as s ". $where;
        $filter['record_count']   = $GLOBALS['db']->getOne($sql);
        $filter['page_count']     = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

        /* 查询 */
        $sql = "SELECT dept_id,u.user_name, rank_id, dept_name, tel,dept_remark,  ".
			      "s.status ".
                "FROM " . $GLOBALS['ecs']->table("dept") . " as s left join " . $GLOBALS['ecs']->table("users") . " as u on s.user_id = u.user_id 
                $where
                ORDER BY " . $filter['sort_by'] . " " . $filter['sort_order']. "
                LIMIT " . ($filter['page'] - 1) * $filter['page_size'] . ", " . $filter['page_size'] . " ";

        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    
	$list=array();
	$res = $GLOBALS['db']->query($sql);
    while ($row = $GLOBALS['db']->fetchRow($res))
	{
		$row['rank_name'] = $rankname_list[$row['rank_id']];
		$row['status_name'] = $row['status']=='1' ? '通过' : ($row['status']=='0' ? "未审核" : "未通过");
		$open = $GLOBALS['db']->getRow("select value from ".$GLOBALS['ecs']->table("dept_config")." where dept_id=".$row['dept_id']." and code='shop_closed'");
		if($open && $open['value'] == 0){
			$row['open'] = 1;
		}else{
			$row['open'] = 0;
		}
		$list[]=$row;
	}

    $arr = array('result' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}
/*
* 入驻商的佣金记录
*/
function rebate_log_list()
{
	$result = get_filter();
    if ($result === false)
    {
        /* 过滤信息 */
        $filter['id'] = intval($_REQUEST['id']);
		$filter['addtime_start'] = !empty($_REQUEST['addtime_start']) ? local_strtotime($_REQUEST['addtime_start']) : 0;
		$filter['addtime_end'] = !empty($_REQUEST['addtime_end']) ? local_strtotime($_REQUEST['addtime_end']." 23:59:59") : 0;
		$filter['status'] = empty($_REQUEST['status']) ? '0' : intval($_REQUEST['status']);
       
        $where = ' WHERE dept_id = '.$filter['id'];
		$where .= $filter['addtime_start'] ? " AND addtime >= '". $filter['addtime_start']. "' " :  " ";
		$where .= $filter['addtime_end'] ? " AND addtime_end <= '". $filter['addtime_end']. "' " :  " ";
		$where .= $filter['status']>0 ? " AND status = '". $filter['status']. "' " : "";

        /* 分页大小 */
        $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

        if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0)
        {
            $filter['page_size'] = intval($_REQUEST['page_size']);
        }
        elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0)
        {
            $filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
        }
        else
        {
            $filter['page_size'] = 15;
        }

        /* 记录总数 */
        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('dept_money_log') . $where;
        $filter['record_count']   = $GLOBALS['db']->getOne($sql);
        $filter['page_count']     = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

        /* 查询 */
        $sql = "SELECT * ".
                "FROM " . $GLOBALS['ecs']->table("dept_money_log") . $where ."
                ORDER BY addtime desc 
                LIMIT " . ($filter['page'] - 1) * $filter['page_size'] . ", " . $filter['page_size'] . " ";

        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

	$list=array();
	$res = $GLOBALS['db']->query($sql);
    while ($row = $GLOBALS['db']->fetchRow($res))
	{
		$row['add_time'] = local_date("Y-m-d H:i:s",$row['addtime']);
		$list[]=$row;
	}

    $arr = array('result' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

/*
删除店铺下所有商品的上传的图片
*/
function delete_dept_pic($suppid){
	global $db,$ecs;

	$sql = "select goods_thumb,goods_img,original_img from ".$ecs->table('goods')." where dept_id=".$suppid;

	$query = $db->query($sql);
	while($row = $db->fetchRow($query)){
		@unlink(ROOT_PATH.$row['goods_thumb']);
		@unlink(ROOT_PATH.$row['goods_img']);
		@unlink(ROOT_PATH.$row['original_img']);
	}

	$sql = "select gg.img_url,gg.thumb_url,gg.img_original from ".$ecs->table('goods_gallery')." as gg,".$ecs->table('goods')." as g where g.dept_id=".$suppid." and g.goods_id=gg.goods_id";

	$query = $db->query($sql);
	while($row = $db->fetchRow($query)){
		@unlink(ROOT_PATH.$row['img_url']);
		@unlink(ROOT_PATH.$row['thumb_url']);
		@unlink(ROOT_PATH.$row['img_original']);
	}
}
?>
