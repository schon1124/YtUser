{* Template Name:登录 *}
<?php echo'<meta charset="UTF-8"><div style="text-align:center;padding:60px 0;font-size:16px;"><h2 style="font-size:60px;margin-bottom:32px;">打开这个网页的是傻逼</h2>吼吼!</div>';die();?>
{template:header}
{template:b_nav_top}
<aside id="pageside" class="dm-sider am-u-lg-2"> 
	{if $zbp->Config('dmam')->page_navi}
		<dl>
		<dd>
		<ul class="am-nav">
		{$zbp->Config('dmam')->page_navi}
		</ul>
		</dd>
		</dl>
	{/if}
	</aside>
	<div class="dm-container am-u-lg-10">
 <div class="admin-content">
    <div class="admin-content-body">
      <div class="am-cf am-padding am-padding-bottom-0">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">密码重置</strong> / <small>Password Reset</small></div>
      </div>

      <hr>
      <div class="am-g am-padding am-padding-top-0">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
 {if TransferHTML($_GET['username'], '[noscript]') && TransferHTML($_GET['hash'], '[noscript]')}
      <div class="am-form">
      <input type="hidden" value="{TransferHTML($_GET['username'], '[noscript]')}" id="edtUserName" name="name">
	  <input type="hidden" value="{TransferHTML($_GET['hash'], '[noscript]')}" id="edtHash" name="hash"/>
	<label for="edtPassWord">新密码:</label>
      <input type="password" id="edtPassWord" name="password" placeholder="Password">
	        </br>
		<label for="edtPassWord">再输一次新密码:</label>
      <input type="password" id="edtPassWord" name="repassword" placeholder="Password">
      </br>
	  <label for="verifycode" class="am-u-sm-3 am-form-label">{$article.verifycode}</label>
	  <input required="required" id="verifycode" type="text" name="verifycode">
		</br>

      <div class="am-cf">
        <input type="submit" onclick="return Resetpassword()" class="am-btn am-btn-primary am-btn-sm am-fl">
       <small class="am-fr am-text-success">下次可别忘记咯</small>
      </div>
      </div>
	  {else}
	  <div class="am-modal am-modal-alert am-modal-active" tabindex="-1" id="my-alert" style="display: block;"><div class="am-modal-dialog"><div class="am-modal-hd">密码重置提醒</div><div class="am-modal-bd">请从邮件点击重置密码链接进入重置密码页面</div><div class="am-modal-footer"><span class="am-modal-btn am-modal-btn-bold">确定</span></div></div></div>
		  {/if}
  </div>

      </div>
    </div>
  </div>
	</div>
{template:footer}