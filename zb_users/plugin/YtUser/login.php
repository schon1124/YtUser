<?php
require_once('global.php');
$appid = $zbp->Config('YtUser')->appid; 
$appkey = $zbp->Config('YtUser')->appkey; 
$redirect_uri = $zbp->host."zb_users/plugin/YtUser/login.php";


if (!isset($_GET['code'])) {
	$state = md5(uniqid(rand(), true));
	$scope = 'get_user_info';
	$login_url = "https://graph.qq.com/oauth2.0/authorize?scope={$scope}&state={$state}&response_type=code&client_id={$appid}&redirect_uri={$redirect_uri}";
	Redirect($login_url);
} else {
    $code = $_GET['code'];
	if (empty($code)) {echo 'Error Get Authorization Code';exit();} 
	$urldata = get_url_contents("https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id={$appid}&client_secret={$appkey}&code={$code}&redirect_uri={$redirect_uri}");
	parse_str($urldata);
	if (empty($access_token)) {if (empty($_SESSION['access_token'])) {echo 'Error Get Access Token';exit();} else {$access_token = $_SESSION['access_token'];} } else {$_SESSION['access_token'] = $access_token;}
	$urldata = get_url_contents("https://graph.qq.com/oauth2.0/me?access_token={$access_token}");
	$urldata = str_replace("callback(", "", $urldata);
	$urldata = str_replace(');', '', $urldata);
	$urldata = json_decode($urldata, true);
	$openid = $urldata['openid'];
	if (empty($openid)) {echo 'Error Get Open ID';exit();} 
	$urldata = get_url_contents("https://graph.qq.com/user/get_user_info?access_token={$access_token}&oauth_consumer_key={$appid}&openid={$openid}&format=json");
	if (empty($urldata)) {echo 'Error Get User Info';exit();} 
	$userinfo = json_decode($urldata, true);
    
    
    if($openid!=""){
                $YtdsSlide_Table='%pre%ytuser';
                $YtdsSlide_DataInfo=array(
                    'id'=>array('tc_id','integer','',0),
                    'uid'=>array('tc_uid','integer','',0),
                    'oid'=>array('tc_oid','string',255,''),
                );
                $where = array(array('=','tc_oid',$openid));
                $sql = $zbp->db->sql->Select($YtdsSlide_Table,'*',$where,null,null,null);
                $array = $zbp->GetListCustom($YtdsSlide_Table,$YtdsSlide_DataInfo,$sql);
                
                if($zbp->user->ID){
                    if (count($array)>0) {
                        Redirect($zbp->host."?Binding");die();
                    }else{
                        $keyvalue=array();
                        $keyvalue['tc_oid']=$openid;
                        $sql = $zbp->db->sql->Update($tysuer_Table,$keyvalue,array(array('=','tc_uid',$zbp->user->ID)));
                        Redirect($zbp->host."?Binding");
                    }
                    
                }

                if (count($array)>0) {
                    $zbpuid = '';
                    foreach ($array as $key => $reg) {
                        $zbpuid .= $reg->uid;
                    }
                    $m=$zbp->members[$zbpuid];
                    if ($m->Status == ZC_MEMBER_STATUS_AUDITING) {
                    $zbp->ShowError(79, __FILE__, __LINE__);
                    die();
                    }
                    if ($m->Status == ZC_MEMBER_STATUS_LOCKED) {
                    $zbp->ShowError(80, __FILE__, __LINE__);
                    die();
                    }
                    $un=$m->Name;
                    if($blogversion>131221){
                        $ps=md5($m->Password . $zbp->guid);
                    }else{
                        $ps=md5($m->Password . $zbp->path);
                    }
                    $addinfo=array();
    				$addinfo['dishtml5']=(int)GetVars('dishtml5', 'POST');
    				$addinfo['chkadmin']=(int)$zbp->CheckRights('admin');
    				$addinfo['chkarticle']=(int)$zbp->CheckRights('ArticleEdt');
    				$addinfo['levelname']=$m->LevelName;
    				$addinfo['userid']=$m->ID;
    				$addinfo['useralias']=$m->StaticName;
                    setcookie("username", $un,0,$zbp->cookiespath);
                    setcookie("password", $ps,0,$zbp->cookiespath);
                    setcookie("addinfo" . str_replace('/','',$zbp->cookiespath), json_encode($addinfo), 0, $zbp->cookiespath);
        			Redirect($zbp->host);
                    die();
                    }else{
                    $guid=GetGuid();
            $member=new Member;
            $member->Guid=$guid;
            $member->Name="yt_".$guid;
            $member->Alias=$userinfo['nickname'];
            $member->Password="0e681aa506fc191c5f2fa9be6abddd01";
            $member->HomePage="";
            $member->Level=5;
            $member->PostTime=time();
            $member->IP=GetGuestIP();
            $member->Metas->Img="";
            $member->Save();
            $get=$member->ID;
            $get=(int)$get;
            $YtdsSlide_Table='%pre%ytuser';
            $DataArr = array(
                'tc_uid'            => $member->ID,
                'tc_oid'            => $openid,
            );
        	$sql= $zbp->db->sql->Insert($YtdsSlide_Table,$DataArr);
        	$zbp->db->Insert($sql);
                $un=$member->Name;
                if($blogversion>131221){
                    $ps=md5($member->Password . $zbp->guid);
                }else{
                    $ps=md5($member->Password . $zbp->path);
                }
                $addinfo=array();
                $addinfo['dishtml5']=(int)GetVars('dishtml5', 'POST');
                $addinfo['chkadmin']=(int)$zbp->CheckRights('admin');
                $addinfo['chkarticle']=(int)$zbp->CheckRights('ArticleEdt');
                $addinfo['levelname']=$member->LevelName;
                $addinfo['userid']=$member->ID;
                $addinfo['useralias']=$member->StaticName;
                setcookie("username", $un,0,$zbp->cookiespath);
                setcookie("password", $ps,0,$zbp->cookiespath);
                setcookie("addinfo" . str_replace('/','',$zbp->cookiespath), json_encode($addinfo), 0, $zbp->cookiespath);
                Redirect($zbp->host);
                die();
            }
    }else{
        Redirect($zbp->host);
    }
} 
?>