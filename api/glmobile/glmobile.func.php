<?php
/*
	dt之家手机版 作者一切归零 QQ:170357286
	http://www.dtjia.top
*/
defined('IN_DESTOON') or exit('Access Denied');
/**
 * 获取相应模块下的数据总数
 * $mid  模块id 例如供应为5
 * $status查询条件 $cache 缓存时间
 */
function gl_module_nums($mid,$status = 'status = 3',$cache = 1800) {
global $db;
if(!$mid) return;
$table = get_table($mid);
$nums = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $status", $cache);
return $nums['num'] ;
}
/**
 * 获取相应模块下对应id的评论数 商城模块不适用
 * $mid  模块id 例如供应为5
 * $itemid  信息id 比如1
 */
function gl_get_comments($mid,$itemid) {
global $db;
$nums = $db->get_one("SELECT COUNT(*) AS num FROM {$db->pre}comment WHERE item_mid='$mid' and item_id='$itemid'");
return $nums['num'] ;
}

/**
 * 获取相应id下对应的分类名称
 */
function gl_cat_name($catid) {
	global $db;
	$catid = intval($catid);
	$catname = '';
    $r = $db->get_one("SELECT catname FROM {$db->pre}category WHERE catid=$catid");
    $catname = $r['catname'];
	return $catname;
}

/**
 * 获取相应id下对应的分类名称
 */
function gl_club_name($catid,$type = 0) {
	global $db;
	$catid = intval($catid);
	$catname = '';
    $r = $db->get_one("SELECT title,post,fans,thumb FROM {$db->pre}club_group WHERE catid=$catid AND status=3");
	if($type == 1){
	$clubname = array();
	$clubname['title'] = $r['title'];
	$clubname['post'] = $r['post'];
	$clubname['fans'] = $r['fans'];
	$clubname['thumb'] = $r['thumb'];
	}else{
    $clubname = $r['title'];
	}
	return $clubname;
}

/**
 * 获取相应id下对应的地区名称
 */
function gl_area_name($areaid) {
	global $db;
	$catid = intval($areaid);
	$areaname = '';
    $r = $db->get_one("SELECT areaid,areaname FROM {$db->pre}area WHERE areaid=$areaid");
    $areaname = $r['areaname'];
	return $areaname;
}

/**
 * 获取自定义分类名称
 */
function gl_type_name($item, $typeid = 0) {
	global $db;
	$typeid = intval($typeid);
	$r = $db->get_one("SELECT typeid,typename FROM {$db->pre}type WHERE item='".$item."' and typeid='$typeid'");
	$typename = $r['typename'];
	return $typename;
}
function gl_exit($error='error',$path='',$message='') {
  exit('{"error":"'.$error.'","path":"'.$path.'","message":"'.$message.'"}');
}

//获取分类父id
function gl_get_parcatid($catid,$url=0) {
	global $db;
		$r = $db->get_one("SELECT parentid FROM {$db->pre}category WHERE catid=$catid");
		if($r['parentid']==0){
			$catid = $catid;
		}else{
		$catid = $r['parentid'];
		}
        if($url){
		$r = $db->get_one("SELECT linkurl FROM {$db->pre}category WHERE catid=$catid");
		$catid = $r['linkurl'];
		}
		return $catid;
}

//获取分类父id
function gl_get_parareaid($areaid,$url=0) {
	global $db;
		$r = $db->get_one("SELECT parentid FROM {$db->pre}area WHERE areaid=$areaid");
		if($r['parentid']==0){
			$areaid = $areaid;
		}else{
		$areaid = $r['parentid'];
		}
        if($url){
		$r = $db->get_one("SELECT linkurl FROM {$db->pre}area WHERE areaid=$areaid");
		$areaid = $r['linkurl'];
		}
		return $areaid;
}

function gl_bdcity($ip) {
	global $DT;
	$ch = curl_init();
    $url = 'http://apis.baidu.com/apistore/iplookupservice/iplookup?ip='.$ip;
    $header = array(
        'apikey: '.$DT['cloud_bdapi_key'].'',
    );
    // 添加apikey到header
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // 执行HTTP请求
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);
	$ipdata = json_decode($res,true);

		$data = $ipdata;
		if($data['errMsg']=='success') {
			$area = '';
			if(isset($ipdata['retData']['country']) && strpos($res, "\u4e2d\u56fd") === false) $area .= $ipdata['retData']['country'];
			if(isset($ipdata['retData']['province'])) $area .= $ipdata['retData']['province'].'&#x7701;';//省
			if(isset($ipdata['retData']['city'])) $area .= $ipdata['retData']['city'].'&#x5E02;';//市
			if(isset($ipdata['retData']['district'])) $area .= $ipdata['retData']['district'];//地区
			//if(isset($ipdata['retData']['carrier'])) $area .= '-'.$ipdata['retData']['carrier'];
			if(isset($ipdata['retData']['city'])) $mycity = $ipdata['retData']['city'];//市
			return $mycity ? convert($mycity, 'UTF-8', DT_CHARSET) : '';
		}
		return 'API Error';
	}

//获取资讯详细内容中的图片地址 并输出
function gl_acontent_thumb($moduleid,$itemid,$nums = 0) {
	global $db, $DT, $DT_TIME;
	$$thumbs = '';
	$table = get_table($moduleid,1);
	$r = $db->get_one("SELECT content FROM {$table} WHERE itemid='$itemid'");
	$content = $r['content'];
	if(!$content) return '';
	$ext = 'jpg|jpeg|png|bmp';
	if(!preg_match_all("/src=([\"|']?)([^ \"'>]+\.($ext))\\1/i", $content, $matches)) return '';
	$mnums = count($matches[2]);
	if($mnums>=$nums) $thumbs = array_slice($matches[2], 0, $nums);
    return $thumbs;
}

//获取详细内容中的图片地址 并输出
function gl_content_thumb($content,$nums = 0) {
	global $DT, $DT_TIME;
	if(!$content) return '';
	$ext = 'jpg|jpeg|gif|png|bmp';
	if(!preg_match_all("/src=([\"|']?)([^ \"'>]+\.($ext))\\1/i", $content, $matches)) return '';
	$mnums = count($matches[2]);
	$thumbs = $matches[2];
	if($mnums>=$nums &$nums) $thumbs = array_slice($matches[2], 0, $nums);
	if($nums==1)$thumbs = $thumbs[0];
    return $thumbs;
}

//时间格式化 传入时间戳格式1464662723
function gl_format_date($time){
    $t = time()-$time;
	$timer = '前';
	if($t<0){
		$t = $time - time();
		$timer = '后';
	}
    $f=array(
        '31536000'=>'年',
        '2592000'=>'个月',
        '604800'=>'星期',
        '86400'=>'天',
        '3600'=>'小时',
        '60'=>'分钟',
        '1'=>'秒'
    );
    foreach ($f as $k=>$v)    {
        if (0 !=$c=floor($t/(int)$k)) {
            return $c.$v.$timer;
        }
    }
}

//时间格式化type 7//月份 8 日期 M月份英文缩写 l星期缩写
function gl_timetoday($time = 0, $type = 6) {
	if(!$time) $time = $GLOBALS['DT_TIME'];
	$types = array('Y-m-d', 'Y', 'm-d', 'Y-m-d', 'm-d H:i', 'Y-m-d H:i', 'Y-m-d H:i:s' ,'m' ,'d' ,'M','l','YmdHi','YmdHis','w','H','H:i');
	//m月份 d日期 M月份英文缩写 l星期缩写
	if(isset($types[$type])) $type = $types[$type];
	$date = '';
	if($time > 2147212800) {		
		if(class_exists('DateTime')) {
			$D = new DateTime('@'.($time - 3600 * intval(str_replace('Etc/GMT', '', $GLOBALS['CFG']['timezone']))));
			$date = $D->format($type);
		}
	}
	return $date ? $date : date($type, $time);
}

//获取当前时间状态及拼音
function gl_get_times($time,$type = 0) {
	if(!$time) $time = $GLOBALS['DT_TIME'];
	//自定义星期数组
	$h = gl_timetoday($time,14);
	if($h < 6){
		$timename = '凌晨';$timepy = 'zaoshang';
    } else if ($h < 9){
		$timename ='早上好';$timepy = 'zaoshang';
	} else if ($h < 12){
		$timename ='上午好';$timepy = 'zaoshang';
	} else if ($h < 14){
		$timename ='中午好';$timepy = 'baitian';
	} else if ($h < 17){
		$timename ='下午好';$timepy = 'xiawu';
	} else if ($h < 19){
		$timename ='傍晚好';$timepy = 'wanshang';
	} else if ($h < 23){
		$timename ='晚上好';$timepy = 'wanshang';
	} else {
		$timename ='夜深了';$timepy = 'wanshang';
	} 
	return $type==1?$timepy:$timename;
}

//获取指定时间戳星期几
function gl_get_week($time) {
	if(!$time) $time = $GLOBALS['DT_TIME'];
	//自定义星期数组
	$number_wk = gl_timetoday($time,13);
    $weekArr = array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
	return $weekArr[$number_wk];
}

//获取商圈分类
function gl_get_club_group($title = '', $catid = 0, $gid = 0, $extend = '') {
    global $db, $_child;
	$select = '';
	//$select .= '<input name="gid" id="group_'.$catid.'" type="hidden" value="'.$catid.'"/>';
    $select .= '<select id="group_'.$catid.'">';
	if($title) $select .= '<option value="0">'.$title.'</option>';
	$condition = " status=3";
	$result = $db->query("SELECT itemid,catid,areaid,title FROM {$db->pre}club_group WHERE $condition ORDER BY itemid ASC");
		while($c = $db->fetch_array($result)) {
			$selected = $c['itemid'] == $gid ? ' selected' : '';
			if($_child && !in_array($c['catid'], $_child)) continue;
			$select .= '<option value="'.$c['itemid'].'"'.$selected.'>'.$c['title'].'</option>';
		}
		$select .= '</select> ';
		return $select;
}

function gl_input_trim($wd) {
	return urldecode(str_replace('%E2%80%86', '', urlencode($wd)));
}

function gl_dojson($data,$msg='') {//返回json信息
  if(is_array($data)){
	  $err = array();
	  $err = $data;
  }else{
	  $err = array();
	  $err['error'] = $data;
	  if($msg)$err['msg'] = $msg;
  }
  if(DT_CHARSET != 'UTF-8') $msg = convert($msg, $CFG['charset'],  'utf-8');
  exit(json_encode($err));
}

function gl_layer($dmessage = errmsg, $dforward = '', $extend = '') {
	global $CFG, $DT;
	exit(include template('layermsg', 'glajax'));
}

function gl_GetDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2) 
{ 
//将角度转为狐度
	$radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度
	$radLat2=deg2rad($lat2);
	$radLng1=deg2rad($lng1);
	$radLng2=deg2rad($lng2);
	$a=$radLat1-$radLat2;
	$b=$radLng1-$radLng2;
	$s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137;
	return $s;
} 

//商家坐标
function gl_comlng($userid,$lone){
	global $db;
	$r = $db->get_one("select item_value from {$db->pre}company_setting where userid=$userid and item_key='map'");
	if($r['item_value']) $long = explode(',', $r['item_value']);
	return $long;
}

function gl_comaddress($lat,$type){
	global $CFG, $DT, $EXT;
	$ak = !empty($EXT['gl_bdmap_ak'])?$EXT['gl_bdmap_ak']:'waKl9cxyGpfdPbon7PXtDXIf';
	$url = 'http://api.map.baidu.com/geocoder/v2/?ak='.$ak.'&location='.$lat.'&output=json&pois=1';
    //return $url;
    $res = dcurl($url);
	$location = json_decode($res,true);
	$locaddr = $location['result']['addressComponent'];
	$addrs = $locaddr['province'].$locaddr['city'].$locaddr['district'].$locaddr['street'];
	if($type==1)$addrs = $location['result']['formatted_address'];
	if($type==2)$addrs = $locaddr['city'];
	if($type==3)$addrs = array('address' => $location['result']['formatted_address'],'city' => $locaddr['city'],'point' => $lat);//获取详细地址、城市及坐标
    return $addrs;
}

function gl_comlocation($address, $area) {
	global $CFG, $DT, $EXT;
	$ak = !empty($EXT['gl_bdmap_ak'])?$EXT['gl_bdmap_ak']:'waKl9cxyGpfdPbon7PXtDXIf';
	$address  = convert($address, DT_CHARSET, 'UTF-8'); //中文转码
	$area = convert($area, DT_CHARSET, 'UTF-8'); //中文转码
    $url = 'http://api.map.baidu.com/geocoder/v2/?ak='.$ak.'&output=json&address='.$address.'&city='.$area.'';
    //return $url;
    $res = dcurl($url);
	$location = json_decode($res,true);
	//var_dump($location);

		if($location['status']==0) {
			
            $point = $location['result']['location']['lng'].','.$location['result']['location']['lat'];
			$compoint = array('0' => $location['result']['location']['lng'], '1' => $location['result']['location']['lat']);
			return $compoint;
		}else{
		return '';
		}
	}

function gl_bdmap_ip($ip,$type=1) {
	global $CFG, $DT ,$EXT;
	$ak = !empty($EXT['gl_bdmap_ak'])?$EXT['gl_bdmap_ak']:'waKl9cxyGpfdPbon7PXtDXIf';
    $url = 'https://api.map.baidu.com/highacciploc/v1?qcip='.$ip.'&qterm=pc&ak='.$ak.'&coord=bd09ll';
    //return $url;
    $res = dcurl($url);
	$location = json_decode($res,true);
	//var_dump($location);

		if($location['result']['error']==161) {
			
            $point = $location['content']['location']['lat'].','.$location['content']['location']['lng'];
			$addrs = gl_comaddress($point,$type);
			return $addrs;
		}else{
		return '';
		}
	}

function gl_con_photo($content) {
	global $EXT;
	if($EXT['gl_conphoto']) {
	$pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/";
    $content = preg_replace($pattern,"<a href='$1'>$0</a>",$content);
	return $content;
	}else{
	return $content;
	}
}

function gl_img_echo($content,$type = 0) {
	$content = gl_con_photo($content);
	//$content = preg_replace('/([^>]*)(height="[0-9]")([^<>]*)/i', '$1$3', $content);
	return preg_replace("/src=([\"|']?)([^ \"'>]+\.(jpg|jpeg|gif|png|bmp))\\1/i", "src=\"image/echoblank.gif\" class=\"imgecho\"  data-echo=\"\\2\"", $content);
}

function gl_thumb_echo($thumb,$type = 1) {
	global $EXT;
	if($EXT['gl_img_echo'] && isset($thumb) && $thumb) $thumb =  'image/echoblank.gif" data-echo="'.gl_thumb_type($thumb,$type);
	return $thumb;
}

// 二级域名跨域问题
function gl_allow_origin(){
	global $EXT, $DT;
    $glorigin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'].'' : '';  
    $glallow_origin = array( 
    preg_replace('#/$#','',$EXT['mobile_domain']),  
    preg_replace('#/$#','',DT_PATH)
);  
    if(in_array($glorigin, $glallow_origin)){  
    header('Access-Control-Allow-Origin:'.$glorigin);       
    }
}

function gl_mobile_homeurl($moduleid, $username, $action=NULL, $typeid=NULL, $page = 1, $item=NULL ,$itemid=NULL) {
	global $EXT, $DT;
	$moduleid = 4;
	$glmobileurl = !empty($EXT['mobile_domain'])?$EXT['mobile_domain']:DT_PATH.'mobile/';
	if(RE_WRITE && $username && $action && $item && $EXT['gl_homepage_html']) return $glmobileurl.$username.'/'.$action.'-'.$item.'.html';
	if(RE_WRITE && $username && $action && $itemid && $EXT['gl_homepage_html']) return $glmobileurl.$username.'/'.$action.'/itemid-'.$itemid.($page > 1 ? '-'.$page : '').'.html';
	if(RE_WRITE && $username && $action && $EXT['gl_homepage_html']){
		if($typeid){
		 return $glmobileurl.$username.'/'.$action.'-'.$typeid.'.html';
		}else{
		 return $glmobileurl.$username.'/'.$action.'/';
		}
	}
	if(RE_WRITE && $username && $EXT['gl_homepage_html']) return $username.'/';
    
	if($username && $action && $item) {
		return 'index.php?moduleid='.$moduleid.'&username='.$username.'&action=type&item='.$item.($page > 1 ? '&page='.$page : '');
	} elseif($username && $action && $itemid) {
		return 'index.php?moduleid='.$moduleid.'&username='.$username.'&action='.$action.'&itemid='.$itemid.($page > 1 ? '&page='.$page : '');
	} else if($username && $action && $typeid) {
		return 'index.php?moduleid='.$moduleid.'&username='.$username.'&action='.$action.'&typeid='.$typeid.($page > 1 ? '&page='.$page : '');
    } else if($username && $action) {
		return 'index.php?moduleid='.$moduleid.'&username='.$username.'&action='.$action.($page > 1 ? '&page='.$page : '');
	} elseif($username) {
		return 'index.php?moduleid='.$moduleid.'&username='.$username.'';	
	} else {
		return 'index.php?moduleid='.$moduleid.($page > 1 ? '&page='.$page : '');
	}
}

function gl_homeurl($moduleid, $catid = 0, $itemid = 0, $page = 1, $action, $username='') {
global $EXT;
if($EXT['gl_homeurl']) return 'index.php?moduleid=4&username='.$username.'&action='.$action.'&itemid='.$itemid;
return mobileurl($moduleid, $catid, $itemid, $page);
}
//获取公司setting表里的值
function gl_get_coming($userid, $key = '', $cache = '') {
	global $db;
	if($key) {
		$r = $db->get_one("SELECT * FROM {$db->pre}company_setting WHERE userid=$userid AND item_key='$key'", $cache);
		return $r ? $r['item_value'] : '';
	} else {
		$setting = array();
		if($cache) {
			$query = $db->query("SELECT * FROM {$db->pre}company_setting WHERE userid=$userid AND item_key<>'mypage'", $cache);
		} else {
			$query = $db->query("SELECT * FROM {$db->pre}company_setting WHERE userid=$userid", $cache);
		}
		while($r = $db->fetch_array($query)) {
			$setting[$r['item_key']] = $r['item_value'];
		}
		return $setting;
	}
}

//获取公司表的值
function gl_get_company($userid, $key = '', $cache = '') {
	global $db;
	if($key) {
		$r = $db->get_one("SELECT `$key` FROM {$db->pre}company WHERE userid=$userid", $cache);
		return $r ? $r[$key] : '';
	} 
}

function gl_utftext($msg) {
	global $EXT, $CFG;
	$msg = $msg;
	if(DT_CHARSET != 'UTF-8') $msg = convert($msg, $CFG['charset'],  'utf-8');
	return $msg;
}		

//获取自定义字段的值
function gl_getbox_name($name,$tb) {
global $db;
$options = array();
$v = $db->get_one("SELECT * FROM {$db->pre}fields WHERE name='$name' and tb='$tb'");
$file=$v['option_value'];
$str = rtrim( trim($file),'*');
$options = explode("*",$str);
foreach($options as $_k) {
$v = explode("|",$_k);
$k = trim($v[0]);
$option[$k] = $v[1];
}
return $option ;
}

//输出自定义字段的网站标题
function gl_fields_tags($fields,$moduleid) {
  global $db ,$MODULE;
  $module = $MODULE[$moduleid]['module'];
  $fields_tb = $module.'_'.$moduleid;
  $queryParts = explode('&', $fields);
  $params = array();
  foreach ($queryParts as $param) {
    $item = explode('=', $param);
	$name = $item[0];
    $r = $db->get_one("SELECT name,title,option_value FROM {$db->pre}fields WHERE name='$name' and tb='$fields_tb'");
	if($r){
	$file=$r['option_value'];
    $str = rtrim(trim($file),'*');
    $options = explode("*",$str);
	$rvalue = explode("|",$options[$item[1]-1]);
	$r['fitemid'] = $rvalue[0];
	$r['fname'] = $rvalue[1];
	//if($r['name']=='cs02') $r['fthis'] = 'b'; 
	//if($r['name']=='cs03') $r['fthis'] = 'c'; 
	$params[] = $r;
    }
  }
  return $params;
}


//匹配地区id
function gl_match_city($ip){
global $db;
$cityname = gl_bdmap_ip($ip,2);
$bd_city = str_replace('市','',$cityname);	
$result = $db->query("SELECT areaid,areaname FROM {$db->pre}area ORDER BY areaid");
        while($r = $db->fetch_array($result)) {
		$r['areaname'] = str_replace('市',"",$r['areaname']);
	    if(preg_match("/".$bd_city."/i", $r['areaname'])) {
	    $cityid = $r['areaid'];
	    $cityname = $r['areaname'];
	    break;
	           }
	}
    return $cityid;
}

//匹配地区id
function gl_match_cityname($city){
global $db;
$cityid = 0;
$bd_city = str_replace('市','',$city);	
$result = $db->query("SELECT areaid,areaname FROM {$db->pre}area ORDER BY areaid");
        while($r = $db->fetch_array($result)) {
		$r['areaname'] = str_replace('市',"",$r['areaname']);
	    if(preg_match("/".$bd_city."/i", $r['areaname'])) {
	    $cityid = $r['areaid'];
	    $cityname = $r['areaname'];
	    break;
	           }
	}
    return $cityid;
}

//匹配分类id 也可以返回linkurl
function gl_match_catname($catname,$moduleid,$islink = 0){
        global $db, $MODULE;
		$listurl = $MODULE[$moduleid]['linkurl'].'search.php?kw='.$catname;
		if($catname!=''&&$catname!='全部'){
		$catid = 0;
$result = $db->query("SELECT catid,catname,linkurl FROM {$db->pre}category where moduleid=$moduleid ORDER BY catid");
        while($r = $db->fetch_array($result)) {
			if(preg_match("/".$catname."/i", $r['catname'])) {
				$catid= $r['catid'];
				break;
			}
		}
		if($islink && $catid>0) {
			$CAT = get_cat($catid);
			$CAT['catid'] = $catid;
			$listurl = $MODULE[$moduleid]['linkurl'].listurl($CAT);
			}
        return ($linkurl && $islink)?$listurl:$catid;
	}else{
		return ($linkurl && $islink)?$MODULE[$moduleid]['linkurl']:0;
	}
}

//手机版im链接
function gl_im_web($id, $style = 0) {
	global $MODULE;
	return $id ? '<a href="chat.php?touser='.$id.'" rel="nofollow"><img src="'.DT_PATH.'api/online.png.php?username='.$id.'&style='.$style.'" title="点击交谈/留言" alt="" align="absmiddle" onerror="this.src=DTPath+\'file/image/web-off.gif\';"/></a>' : '';
}

function gl_hidename($str) { //用户名、邮箱、手机账号中间字符串以*隐藏 
    if (strpos($str, '@')) { 
        $email_array = explode("@", $str); 
        $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀 
        $count = 0; 
        $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count); 
        $rs = $prevfix . $str; 
    } else { 
        $pattern = '/(1[3458]{1}[0-9])[0-9]{4}([0-9]{4})/i'; 
        if (preg_match($pattern, $str)) { 
            $rs = preg_replace($pattern, '$1****$2', $str); // substr_replace($name,'****',3,4); 
        } else { 
            $rs = substr($str, 0, 3) . "***" . substr($str, -1); 
        } 
    } 
    return $rs; 
}

//按照拼音顺序排列
function gl_pyorder($maincat) {
    $sort = array();
    foreach($maincat as $key) {
    $sort[] = $key['letter'];
    }
    array_multisort($sort, SORT_ASC, $maincat);
    return $maincat; 
}

//获取中大图 1中图 2大图
function gl_thumb_type($thumb,$type) {
    if($type==2){
	    $thumb = str_replace('.thumb.'.file_ext($thumb), '', $thumb);
	}elseif($type==1){
		$thumb = str_replace('.thumb.'.file_ext($thumb), '.middle.'.file_ext($thumb), $thumb);
	}else{
		$thumb = $thumb;
	}
    return $thumb; 
}

function gl_trimall($str)//删除空格
{
    $qian=array(" ","　","\t","\n","\r");
    $hou=array("","","","","");
    return str_replace($qian,$hou,$str); 
}


//生成汉字拼音 //type 2为首字母 1为简写 默认全拼
function gl_text_pinyin($text,$type) {
	require_once DT_ROOT.'/api/glmobile/pinyin.php';
    if($type==2){
	    $text = gl_pinyin($text,'one');
	}else if($type==1){
		$text = gl_pinyin($text,'first');
	}else{
		$text = gl_pinyin($text,'all');
	}
    return $text; 
}

//生成地区js type 1为全部 2为省市
function gl_areajs($type=2){
	global $db;
	    $condition = ($type==2)?'child=1 or parentid=0':'parentid>=0';
		$data = '';
        $data .= 'allCity = ["';
		$areadata = '';
		$result = $db->query("SELECT areaid,areaname FROM {$db->pre}area WHERE {$condition} ORDER BY listorder desc,areaid desc");
		while($r = $db->fetch_array($result)) {
			$r['areaname'] = gl_trimall($r['areaname']);
			$pyall = gl_trimall(gl_text_pinyin($r['areaname']));
			$pyjx = gl_trimall(gl_text_pinyin($r['areaname'],1));
			$r['arealist'] = $r['areaname'].'|'.$pyall.'|'.$pyjx.'|'.$r['areaid'];
			$areadata .= '"'.$r['arealist'].'",';
		}
		$data .= substr($areadata, 1, -1);
		$data .= '];';
		$datajs = $type==2?'glallone.js':'glallcity.js';
		file_put(DT_ROOT.'/file/script/'.$datajs.'', $data);

}

function gl_is_https(){
        if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
        {
            return TRUE;
        }
        elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        {
            return TRUE;
        }
        elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
        {
            return TRUE;
        }
  
        return FALSE;
    }

//获得指定模块下 指定信息的某个信息	
function gl_item_info($moduleid, $key = 'itemid', $from = 'itemid') {
	global $db;
	$table = get_table($moduleid);
	if($moduleid==4){
	$r = $db->get_one("SELECT `$from` FROM {$table} WHERE username='$key'");
	}else{
	$r = $db->get_one("SELECT `$from` FROM {$table} WHERE itemid='$key'");
	}
	return $r[$from];
}

//获得指定信息被打赏次数	
function gl_dashang_nums($moduleid,$itemid,$username) {
	global $db;
	$condition = ($username) ? " username='$username' and  moduleid=$moduleid and itemid=$itemid" : " moduleid=$moduleid and itemid=$itemid";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$db->pre}extend_dashang WHERE $condition");
	return $r['num'];
}

//截断 $sourcestr传入的字符串 $cutlength 截取长度
function gl_cut_str($sourcestr,$cutlength) {
	$returnstr='';
	$i=0;
	$n=0;
	$str_length=strlen($sourcestr);//字符串的字节数
	while (($n<$cutlength) and ($i<=$str_length)) {
		$temp_str=substr($sourcestr,$i,1);
		$ascnum=Ord($temp_str);//得到字符串中第$i位字符的ascii码
		if ($ascnum>=224)    //如果ASCII位高与224，
		{
		$returnstr=$returnstr.substr($sourcestr,$i,3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
		$i=$i+3;            //实际Byte计为3
		$n++;            //字串长度计1
		}
		elseif ($ascnum>=192) //如果ASCII位高与192，
		{
		$returnstr=$returnstr.substr($sourcestr,$i,2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
		$i=$i+2;            //实际Byte计为2
		$n++;            //字串长度计1
		}
		elseif ($ascnum>=65 && $ascnum<=90) //如果是大写字母，
		{
		$returnstr=$returnstr.substr($sourcestr,$i,1);
		$i=$i+1;            //实际的Byte数仍计1个
		$n++;            //但考虑整体美观，大写字母计成一个高位字符
		}
		else                //其他情况下，包括小写字母和半角标点符号，
		{
		$returnstr=$returnstr.substr($sourcestr,$i,1);
		$i=$i+1;            //实际的Byte数计1个
		$n=$n+0.5;        //小写字母和半角标点等与半个高位字符宽…
		}
	}
	if ($str_length>$cutlength){
		$returnstr = $returnstr . '…';//超过长度时在尾处加上省略号
	}
	return $returnstr;
}

//获取主分类
function gl_get_maincat($catid, $moduleid, $level = -1) {
	global $db;
	$catid = intval($catid);
	$condition = $catid ? "parentid=$catid" : "moduleid=$moduleid AND parentid=0";
	if($level >= 0) $condition .= " AND level=$level";
	$cat = array();
	$result = $db->query("SELECT catid,catname,catdir,child,style,linkurl,item,letter FROM {$db->pre}category WHERE $condition ORDER BY listorder,catid ASC", 'CACHE');
	while($r = $db->fetch_array($result)) {
		$cat[] = $r;
	}
	return $cat;
}

function gl_get_mainarea($areaid) {
	global $db;
	$areaid = intval($areaid);
	$are = array();
	$result = $db->query("SELECT areaid,areaname,child FROM {$db->pre}area WHERE parentid=$areaid ORDER BY listorder,areaid ASC", 'CACHE');
	while($r = $db->fetch_array($result)) {
		$are[] = $r;
	}
	return $are;
}

//获取分类属性
function gl_cate_options($catid) {
	global $db;
	$catid = intval($catid);
	$option = array();
	$result = $db->query("SELECT * FROM {$db->pre}category_option WHERE catid=$catid ORDER BY listorder ASC,oid ASC");
	while($r = $db->fetch_array($result)) {
		$r['options'] = explode('|', str_replace('(*)', '', $r['value']));
		$option[] = $r;
	}
	//var_dump($option);
	return $option;
    
}

//获取分类属性值 catid分类id $opid分类属性id $options分类属性备选值序号 $type分类属性其他值
function gl_cate_opname($catid,$opid,$options,$type) {
	global $db;
	$catid = intval($catid);
	$opid = intval($opid);
	$options = isset($options)?$options:0;
	$r = $db->get_one("SELECT value,extend FROM {$db->pre}category_option WHERE catid=$catid AND oid=$opid");
	$option = explode('|', str_replace('(*)', '', $r['value']));
	$extend = explode('|',$r['extend']);
	 //return var_dump(option);
	if($type) return $extend[$options]; 
	return $option[$options];
    
}

function gl_m_style($name, $title = '', $groupid = 0, $itemid = 0, $extend = '') {
	global $db, $L;
	$title or $title = $L['choose'];
	$select = '<select name="'.$name.'" '.$extend.'><option value="0">'.$title.'</option>';
	$result = $db->query("SELECT * FROM {$db->pre}mstyle ORDER BY listorder DESC,itemid DESC");
	while($r = $db->fetch_array($result)) {
		$select .= '<option value="'.$r['itemid'].'"'.($r['itemid'] == $itemid ? ' selected' : '').'>'.$r['title'].'</option>';
	}
	$select .= '</select>';
	return $select;
}

//获取各自模块的order by
function gl_get_morder($moduleid) {
	$MOD = cache_read('module-'.$moduleid.'.php');
	$order = isset($MOD['order'])?$MOD['order']:'addtime desc';
	return $order;
}

//获取排序方式的名称
function gl_get_ordername($orderid) {
   $orderid = isset($orderid) ? intval($orderid) : 0;
   switch($orderid) {
	    case '1':
         $ordername = '最新发布';
		break;
	    case '2':
		 $ordername = '人气优先';
	    break;
	    case '3':
         $ordername = '价格最高';
	    break;
	    case '4':
         $ordername = '价格最低';
	    break;
	    default:
		 $ordername = '排序方式';
	    break;
		}
	 return $ordername;
}

//获取会员组名称
function gl_group_name($groupid) {
   $GROUP = cache_read('group.php');
   $groupname = $GROUP[$groupid][groupname];
   return $groupname;
}

/** 生成短网址 
* @param String $url 原网址 
* @return String 
*/ 
function gl_smallurl($url){ 
   $code = sprintf('%u', crc32($url)); 
   $surl = ''; 
   while($code){ 
    $mod = $code % 62; 
    if($mod>9 && $mod<=35){ 
    $mod = chr($mod + 55); 
    }elseif($mod>35){ 
    $mod = chr($mod + 61); 
    } 
    $surl .= $mod; 
    $code = floor($code/62); 
   } 
return $surl; 
} 

//匹配手机版路径
function gl_cat_pos($CAT, $str = ' &raquo; ', $target = '') {
	global $MODULE, $db, $DT_TOUCH, $EXT;
	if(!$CAT) return '';
	$arrparentids = $CAT['arrparentid'].','.$CAT['catid'];
	$arrparentid = explode(',', $arrparentids);
	$pos = '';
	$target = $target ? ' target="_blank"' : '';	
	$CATEGORY = array();
	$result = $db->query("SELECT catid,moduleid,catname,linkurl FROM {$db->pre}category WHERE catid IN ($arrparentids)", 'CACHE');
	while($r = $db->fetch_array($result)) {
		$CATEGORY[$r['catid']] = $r;
	}
	foreach($arrparentid as $catid) {
		if(!$catid || !isset($CATEGORY[$catid])) continue;
		if($DT_TOUCH){
			$pos .= '<a href="'.mobileurl($CATEGORY[$catid]['moduleid'],$CATEGORY[$catid]['catid']).'">'.$CATEGORY[$catid]['catname'].'</a>'.$str;
			}else{
			$pos .= '<a href="'.$MODULE[$CATEGORY[$catid]['moduleid']]['linkurl'].$CATEGORY[$catid]['linkurl'].'"'.$target.'>'.$CATEGORY[$catid]['catname'].'</a>'.$str;
			}
	}
	$_len = strlen($str);
	if($str && substr($pos, -$_len, $_len) === $str) $pos = substr($pos, 0, strlen($pos)-$_len);
	return $pos;
}

//数据导入匹配分类属性
function gl_match_catoption($postppt, $moduleid, $catid, $itemid) {
	global $db;
	$OP = gl_cate_options($catid);
	$ppt = array();
	foreach($OP as $v) {
		if($v['type'] > 1 && $v['search']) $ppt[] = $v;
	}
	$success = 0;
    $pptword = '';
	if(is_array($postppt)) {
	  foreach($postppt as $k=>$p) {
       foreach($ppt as $o) {
	    $option = explode('|', str_replace('(*)', '', $o['value']));
	     if(in_array($p, $option)) {
	     $fond = array_search($p,$option);
	     $oid = $o['oid'];
	     $pptword .= 'O'.$o['oid'].':'.$p.';';
	     $db->query("INSERT INTO {$db->pre}category_value (oid,moduleid,itemid,value) VALUES ('$oid','$moduleid','$itemid','$p')");
		 $success++;
	   }
      }
	 }
   }
   	if($pptword) $db->query("UPDATE ".get_table($moduleid)." SET pptword='$pptword' WHERE itemid=$itemid");
	
}

//中文分词
function gl_get_tags($title){
   $title = gl_clearhtml($title);
   if(!class_exists('PSCWS4')){ 
  require DT_ROOT.'/api/pscws4/pscws4.class.php';
    }
  $pscws = new PSCWS4();
  $pscws->set_dict(DT_ROOT.'/api/pscws4/dict.utf8.xdb');
  $pscws->set_rule(DT_ROOT.'/api/pscws4/rules.utf8.ini');
  $pscws->set_charset('utf8');
  $pscws->set_ignore(true);
  $pscws->send_text($title);
  $words = $pscws->get_tops(5);
  $tags = array();
  foreach ($words as $val) {
    $tags[] = $val['word'];
    }
    $pscws->close();
   return $tags;
}

//通过标题获取tag 并输出
function gl_title_tags($title,$target = '',$url = ''){
	    $keytags = gl_get_tags($title);	
		$target = $target ? ' target="_blank"' : '';
		foreach($keytags as $v) {
		if(!$v) continue;
		$tags .= '<a href="search.php?kw='.urlencode($v).'" '.$target.' class="glalltag">'.$v.'</a>';
	}
	return $tags;
	
}

//获取tag 并输出
function gl_all_tags($tag,$target = '',$url = '',$nums='',$nolink=''){
	if(!$tag) return;
	if($nums) $nums = $nums -1;
	if($url) $url = $url.'/';
	$keytags = $tag ? explode(' ', $tag) : array();
	$target = $target ? ' target="_blank"' : '';
	foreach($keytags as $k=>$v) {
		if(!$v) continue;
		if($nums && $k>$nums) continue;
		if($nolink){
			$tags .= '<span class="glalltag">'.$v.'</span>';
		}else{
			$tags .= '<a href="'.$url.'search.php?kw='.urlencode($v).'" '.$target.' class="glalltag">'.$v.'</a>';
		}
	}
	return $tags;
	
}

//获取itemid文章tag 并输出
function gl_article_tags($itemid,$tag){
	global $db;
	if(!$tag){
	$r = $db->get_one("SELECT tag FROM {$db->pre}article_21 WHERE itemid=$itemid");
    $tag = $r['tag'];
	}
	$keytags = $tag ? explode(' ', $tag) : array();
	
		foreach($keytags as $v) {
		if(!$v) continue;
		$tags .= '<a href="search.php?kw='.urlencode($v).'" class="newstag">'.$v.'</a>';
	}
	return $tags;
	
}

//获取公司主营行业并输出
function gl_business_tags($username,$business){
	global $db ,$MODULE;
	$keytags = $business ? explode(' ', $business) : array();
	foreach($keytags as $v) {
		if(!$v) continue;
		$tags .= '<a href="'.$MODULE[4]['linkurl'].'home.php?action=search&homepage='.$username.'&kw='.urlencode($v).'&file=sell" class="businesstag">'.$v.'</a>';
	}
	return $tags;
	
}

function gl_pages($totle,$page=1,$pagesize=20,$shownum=0,$showtext=0,$showselect=0,$shownext=0,$showlvtao=7,$url='') {
	global $DT_URL, $DT, $L;
	if($demo) {
		$demo_url = $demo;
		$home_url = str_replace('{destoon_page}', '1', $demo_url);
	} else {
		if(defined('DT_REWRITE') && RE_WRITE && $_SERVER["SCRIPT_NAME"] && strpos($DT_URL, '?') === false) {
			$demo_url = $_SERVER["SCRIPT_NAME"];
			$demo_url = str_replace('//', '/', $demo_url);//Fix Nginx
			$mark = false;
			if(substr($demo_url, -4) == '.php') {
				if(strpos($_SERVER['QUERY_STRING'], '.html') === false) {
					$qstr = '';
					if($_SERVER['QUERY_STRING']) {					
						if(substr($_SERVER['QUERY_STRING'], -5) == '.html') {
							$qstr = '-'.substr($_SERVER['QUERY_STRING'], 0, -5);
						} else {
							parse_str($_SERVER['QUERY_STRING'], $qs);
							foreach($qs as $k=>$v) {
								$qstr .= '-'.$k.'-'.rawurlencode($v);
							}
						}
					}
					$demo_url = substr($demo_url, 0, -4).'-htm-page-{destoon_page}'.$qstr.'.html';
				} else {
					$demo_url = substr($demo_url, 0, -4).'-htm-'.$_SERVER['QUERY_STRING'];
					$mark = true;
				}
			} else {
				$mark = true;
			}
			if($mark) {
				if(strpos($demo_url, '%') === false) $demo_url =  rawurlencode($demo_url);
				$demo_url = str_replace(array('%2F', '%3A'), array('/', ':'), $demo_url);
				if(strpos($demo_url, '-page-') !== false) {
					$demo_url = preg_replace("/page-([0-9]+)/", 'page-{destoon_page}', $demo_url);
				} else {
					$demo_url = str_replace('.html', '-page-{destoon_page}.html', $demo_url);
				}
			}
			$home_url = str_replace('-page-{destoon_page}', '-page-1', $demo_url);
		} else {
			$DT_URL = str_replace('&amp;', '&', $DT_URL);
			$demo_url = $home_url = preg_replace("/(.*)([&?]page=[0-9]*)(.*)/i", "\\1\\3", $DT_URL);
			$s = strpos($demo_url, '?') === false ? '?' : '&';
			$demo_url = $demo_url.$s.'page={des'.'toon_page}';
			if(defined('DT_ADMIN') && strpos($demo_url, 'sum=') === false) $demo_url = str_replace('page=', 'sum='.$items.'&page=', $demo_url);
		}
	}
	$pages = '';
	include DT_ROOT.'/api/glpages.func.php';
	return $pages;
;
}

function gl_listpage($total, $page = 1, $perpage = 20, $html = 0, $demo ='') {
	global $DT;
    $demo_url = $_SERVER["SCRIPT_NAME"];
    $demo_url = str_replace('//', '/', $demo_url);//Fix Nginx
    $qstr = '';
    if($_SERVER['QUERY_STRING']) {					
    parse_str($_SERVER['QUERY_STRING'], $qs);
    foreach($qs as $k=>$v) {
    $qstr .= '/'.rawurlencode($v);
    }
    $demo_url = substr($demo_url, 0, -4).$qstr.'/';
	$demo_url = str_replace('tag/', '', $demo_url);
    //$demo_url = str_replace('list/', '', $demo_url);
    //$demo_url = str_replace($page.'/', '', $demo_url);
    }
	if($total <= $perpage) return '';
	$items = $total;
	$total = ceil($total/$perpage);
	if($page < 1 || $page > $total) $page = 1;
	if($page > $total) $page = $total;
	switch($html) {
	    case '1':
         $url = "?page={page}";
		break;
		case '2':
         $url = 'index_{page}.html';
		break;
		case '3':
         $url = $demo.'_{page}.html';
		break;
		default:
		$url = $demo_url.'{page}/';
		break;
	}
	$pages = '';
	include DT_ROOT.'/api/gllistpages.func.php';
	$glpages = new page($items, $perpage, $page, $url, 2); 
	$pages = $glpages->myde_write();
	return $pages;
}

function gl_clearhtml($msg="")
{
if(empty($msg))
{
return false;
}
$msg = strip_tags($msg);
$msg = str_replace('&nbsp;',"",$msg);
$msg = str_replace('&quot;',"",$msg);
$msg = str_replace('&#039;',"",$msg);
$msg = str_replace('&amp;',"",$msg);
$msg = str_replace('&lt;',"",$msg);
$msg = str_replace('&gt;',"",$msg);
$msg = str_replace('&','',$msg);
$msg = str_replace("'","",$msg);
$msg = str_replace('"','',$msg);
return $msg;
}

//匹配微信分享图标
function gl_share_icon($thumb, $content) {
	if(strpos($thumb, '.thumb.') !== false) return substr($thumb, 0, strpos($thumb, '.thumb.'));
	if($thumb) return $thumb;
	$thumbs = gl_content_thumb($content,1);
	if($thumbs) return $thumbs;
	return DT_PATH.'apple-touch-icon-precomposed.png';
}

//匹配PC端地址
function gl_get_pcurl($moduleid,$catid,$itemid,$page,$username='',$action='') {
	global $DT, $MODULE, $MOD, $db;
	if($itemid) {
		$table = get_table($moduleid);
		$r = $db->get_one("SELECT linkurl FROM {$table} WHERE itemid=$itemid");
		return $MODULE[$moduleid]['linkurl'].$r['linkurl'];
	} else if($catid) {
		$CAT = get_cat($catid);
		listurl($CAT);
		return $MODULE[$moduleid]['linkurl'].listurl($CAT,$page);
	} else {
		if($moduleid==4 && $username) return userurl($username);
		return $MODULE[$moduleid]['linkurl'];
	}
}

//防止刷点击数
function gl_uphit($table,$itemid){
	global $DT, $DT_IP, $session;
	$refresh = '';
    if(!is_object($session)) $session = new dsession();
        $allowTime = ($DT['cache_hits'])?$DT['cache_hits']:1800;
        $allowT = md5($DT_IP.$table.$itemid);
        if(!isset($_SESSION[$allowT])){
            $refresh = 1;
            $_SESSION[$allowT] = time();
        }elseif(time() - $_SESSION[$allowT]>$allowTime){
            $refresh = 1;
            $_SESSION[$allowT] = time();
        }else{
            $refresh = '';
        }
        return $refresh;
}

function gl_fields_html($left = '<li class="aui-list-item gl_fields_html"><div>', $right = '<li></div><li>', $values = array(), $fd = array()) {
	extract($GLOBALS, EXTR_SKIP);
	if($fd) $FD = $fd;
	$html = '';
	foreach($FD as $k=>$v) {
		if(!$v['display']) continue;
		if(!defined('DT_ADMIN') && !$v['front']) continue;
		$html .= gl_fields_show($k, $left, $right, $values, $fd);
	}
	return $html;
}

function gl_fields_show($itemid, $left = '<li class="aui-list-item gl_fields_html"><div>', $right = '</div><li>', $values = array(), $fd = array()) {
	extract($GLOBALS, EXTR_SKIP);
	if($fd) $FD = $fd;
	if(!$values) {
		if(isset($item)) $values = $item;
		if(isset($user)) $values = $user;
	}
	$leftred = '<li class="aui-list-item gl_fields_html"><div>';
	$html = '';
	$v = $FD[$itemid];
	$value = $v['default_value'];
	if(isset($values[$v['name']])) {
		$value = $values[$v['name']];
	} else if($v['default_value']) {
		eval('$value = "'.$v['default_value'].'";');
	}
	if(!$value) return;
	if($v['html'] == 'hidden') {
		$html .= '';
	} else {
		if($v['input_limit']) {
			$html .= $leftred;
		} else {
			$html .= $left;
		}
		$html .= '<span>'.$v['title'].'：';
		switch($v['html']) {
			case 'text':
			   if($value) {
				strpos($value, '://') === false ? $html .= ''.$value.'</span>' : $html .= '<a href="'.$value.'" title="'.$v['title'].'">'.$v['title'].'</a></span>';
			   }
			break;
			case 'textarea':
				$html .= ''.$value.'</span>';			
			break;
			case 'select':
				$html .= ''.$value.'</span>';
			break;
			case 'radio':
			    if($v['option_value']) {
					$value = explode(',', $value);
					$rows = explode("*", $v['option_value']);
					foreach($rows as $rw => $row) {
						if($row) {
							$cols = explode("|", trim($row));
							if(in_array($cols[0], $value)) $html .= $cols[1].' ';
						}
					}
					$html .= '</span>';
				}
			break;
			case 'checkbox':
			    if($v['option_value']) {
					$value = explode(',', $value);
					$rows = explode("*", $v['option_value']);
					foreach($rows as $rw => $row) {
						if($row) {
							$cols = explode("|", trim($row));
							if(in_array($cols[0], $value)) $html .= $cols[1].' ';
						}
					}
					$html .= '</span>';
				}
			break;
			case 'date':
				$html .= ''.$value.'</span>';
			break;
			case 'thumb':
			$html .= '</span><span><a href="'.$value.'" title="'.$v['title'].'"><img src="'.$value.'" class="gl-img-contain" /></a></span></span>';
			break;
			case 'file':
			break;
			case 'editor':
			break;
			case 'area':
				$html .= ''.gl_area_name($value).'</span>';
			break;
		}
	}
	//if($fieldsdate)return $html.gl_fieldsdate($fieldsdate);
	return $html;
}

//根据生日获取年龄 格式:1999-09-09
function gl_get_age($birthday){
  list($year,$month,$day) = explode("-",$birthday);
  $year_diff = date("Y") - $year;
  $month_diff = date("m") - $month;
  $day_diff  = date("d") - $day;
  if ($day_diff < 0 || $month_diff < 0)
   $year_diff--;
  return $year_diff;
}

function gl_writelog($filename,$data)
{
    $open = fopen($filename, "a");
    fwrite($open, $data."\r\n");
    fclose($open);
}

//百度mip匹配详情
function gl_bdmip_content($content) {
	$content = preg_replace("/style=.+?['|\"]/i",'',$content);
	$content = preg_replace("/<img\s*src=(\"|\')(.*?)\\1[^>]*>/is",'<mip-img src="$2" />', $content);
	return $content;
}

//tags调用传入输出 比如是后台的类型 例如type=".$MOD[type]." 在列表里调用gl_array_data($type,$t[typeid])
function gl_array_data($data,$number,$path = '|') {
	if(!$data)return false;
	$data = trim($data);
	$arrdata = explode($path,$data);
	return $arrdata[$number];
}

//destoon默认消息推送
function gl_push_chat($touser,$word,$ext = 'jpg|jpeg|gif|png|bmp'){
	global $DT, $EXT , $_userid;
     if(!class_exists('Gateway')){require DT_ROOT.'/api/glchat/Gateway2.0.7.php';}
     $Gateway = new Gateway();
     Gateway::$registerAddress = $EXT['gl_chat_ws'];
	 $toid = get_user($touser, 'username', 'userid');
	 if($toid == $_userid)return false;
	 $username = get_user($_userid, 'userid', 'username');
	 $msg['username'] = $username;
     $msg['avatar'] = gl_useravatar($username, 'smaill');
     $msg['id'] = $_userid;
     $msg['content'] = $word;
	 if(preg_match_all("/([\"|']?)([^ \"'>]+\.($ext))\\1/i", $word, $matches)){
	  //$msg['content'] = '{"code":0,"msg": "ok","data":{"src":"'.$word.'"}}';
	  $msg['content'] = 'img['.$word.']';
	 }
     $msg['type'] = 'friend';
     $chatMessage['type'] = 'getMessage';
     $chatMessage['content'] = $msg;
	 $chatMessage['fromtype'] = ($DT_TOUCH)?'mobile':'pc';
	 Gateway::sendToUid($toid, json_encode($chatMessage));

}

/*数组按照字符串长短进行排序 usort($a,"my_sort");
gl_array_sorts 从小到大
gl_array_sortb 从大到小
*/
function gl_array_sorts($a,$b)
{
if ($a==$b) return 0;
   return (strlen($a) > strlen($b))?1:-1;
}

function gl_array_sortb($a,$b)
{
if ($a==$b) return 0;
   return (strlen($a) > strlen($b))?-1:1;
}

/* 生成二维码及名片*/
function gl_qrcode($url,$type = 0){
  require DT_ROOT.'/api/glmobile/phpqrcode.php';
  ob_start();
  QRcode::png($url, false , '1', 8 , $margin = 1, $saveandprint=true);
  $data =ob_get_contents();
  ob_end_clean();
  return "data:image/jpeg;base64,".base64_encode($data);
}

//判断是否有违禁词并返回
function gl_banword($WORD, $string, $type) {
	$string = stripslashes($string);
	foreach($WORD as $v) {
		$v[0] = preg_quote($v[0]);
		$v[0] = str_replace('/', '\/', $v[0]);
		$v[0] = str_replace("\*", ".*", $v[0]);
		if($v[2]) {
			if(preg_match("/".$v[0]."/i", $string)) return($v[0]);
		}
	}
}

/* 获取图片*/
function gl_getimgs($str) {
    $reg = '/((http|https):\/\/)+(\w+\.)+(\w+)[\w\/\.\-]*(jpg|gif|png)/';
    $matches = array();
    preg_match_all($reg, $str, $matches);
    foreach ($matches[0] as $value) {
        $data[] = $value;
    }
    return $data;
}

/* 会员发布信息汇总*/
function gl_items_list($moduleid,$itemid){
	global $db;
	if(!$moduleid || !$itemid) return;
	$db->query("INSERT INTO {$db->pre}items_list (mid,itemid) VALUES ($moduleid,$itemid)");

}

/* https适配*/
function gl_https_url($url){
	if(!$url) return;
	$url = str_replace('https:', '', $url);
    $url = str_replace('http:', '', $url);
    return $url;
}

/* 手机版返回地址适配*/
function gl_back_link($url,$js='javascript:history.go(-1)'){
	if(!$url) return;
	if($_SERVER['HTTP_REFERER']){
		$back_link = $js;
	}else{
	    $back_link = $url;
	}
    return $back_link;
}

/* 手机端信息提示页面*/
function gl_mobmsg($dmessage = errmsg, $dforward = 'goback', $dtime = 1) {
	global $CFG, $DT, $glmobileurl;
	if(!$dmessage && $dforward && $dforward != 'goback') dheader($dforward);
	exit(include template('glmsg', 'mobile'));
}

/* 获取页面url参数*/
function gl_geturl_value($url) {
    $result = array();
    $mr = preg_match_all('/(\?|&)(.+?)=([^&?]*)/i', $url, $matchs);
    if ($mr !== FALSE) {
        for ($i = 0; $i < $mr; $i++) {
            $result[$matchs[2][$i]] = $matchs[3][$i];
        }
    }
    return $result;
}

function gl_array_ani($number=1){
$animated = array('bounce','flash','pulse','rubberBand','shake','swing','tada','wobble','bounceIn','bounceInDown','bounceInLeft','bounceInRight','bounceInUp','fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp','fadeInUpBig','flip','flipInX','flipInY','lightSpeedIn','rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight','hinge','jackInTheBox','rollIn','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp','slideInDown','slideInLeft','slideInRight','slideInUp');
$random_keys=array_rand($animated);
return $animated[$random_keys];
}

//dt之家php函数结束
?>