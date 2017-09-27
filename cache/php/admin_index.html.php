<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin Index</title>
	<?php include __PUBTPL__.'head.html'; ?>
<style type="text/css">
.bigbox{
	margin: 0px auto;
}
#add_box{
	border: 1px solid #fff;
	float: left;
	margin-left: 360px;
	width: 50%;
}
#addtab{
	width: 100%;
	margin-bottom: 0px;
	border-collapse: collapse;
}
#addtab td,#addtab th{
	text-align: center;
}
#addtab td{
	/*padding-top: 20px;*/
	 vertical-align:middle;
	 margin-top: 15px;
}
.ptd{
	width: 20%;
	color: #fff;
	line-height: 4em;
	border-right: 1px solid #fff;
}
.table tr th{
	background-color: #707070;
	color: #fff;
}
.table tr{
	height: 5em;
	line-height: 5em;
	overflow: hidden;
	margin:50px 0;
}
</style>
</head>
<body>
<!-- 网站头 -->
<?php include TCACHE.'nav.html'; ?>

<div class="content_box">
	

	<!-- 2.添加框div -->
	<div id="add_box">
		<table id="addtab" class="table">
			<tr style="line-height: 3em;height: 3em">
				<th class="ptd">内容</th>
				<th>值</th>
			</tr>
			<tr>
				<td class="ptd">User</td>
				<td>
					<input type="text" name="uname" id="" class="form-control" disabled="disabled" value="<?php echo $this->value['adminInfo']['uname']; ?>">
				</td>
			</tr>

			<tr>
				<td class="ptd">IP</td>
				<td>
					<input type="text" name="uname" id="" class="form-control" disabled="disabled" value="<?php echo $this->value['adminInfo']['lastip']; ?>">
				</td>
			</tr>

			<tr>
				<td class="ptd">上次登录</td>
				<td>
					<input type="text" name="uname" id="" class="form-control" disabled="disabled" value="<?php echo $this->value['adminInfo']['time']; ?>">
				</td>
			</tr>

			<tr id="qx_div" style="display: none">
				<td class="ptd">权限</td>
				<td>
					
		<?php if(count($this->value['adminmenu'])>0){ ?>
		<?php foreach($this->value['adminmenu'] as $this->value['m']){ ?>
			
					<label class="checkbox-inline">
				        <input type="checkbox" name="ps" value="<?php echo $this->value['m']['nw_field']; ?>" 
					        <?php foreach($this->value['mPermissionList'] as $ss){?>
					        	<?php if(strtolower($this->value['m']['nw_field']) == substr($ss,0,strlen($this->value['m']['nw_field']))): ?>
									checked="checked"
								<?php endif; ?>
							<?php } ?> 
						>
				        <font class="label"><?php echo $this->value['m']['name']; ?></font>				        
<!-- 想找你买的不会介意这点钱,不想买的再便宜也不会买,你卖便宜了.反而让想买的人觉得逼格不够 -->
				    </label>
				    <?php } ?><?php } ?>
				</td>
			</tr>
			
			
			<tr id="old_div" style="display: none">
				<td class="ptd">旧密码</td>
				<td>
					<input type="password" name="oldPassWD" id="oldPassWD" class="form-control"  >
				</td>
			</tr>

			<tr id="new_div" style="display: none">
				<td class="ptd">新密码</td>
				<td>
					<input type="password" name="newPassWD" id="newPassWD" class="form-control" >
				</td>
			</tr>

			<tr style="padding-top: 10px">
				<td class="ptd" style="line-height: 36px">操作</td>
				<td>
					<input class="btn" id="bt1"  style="width: 40%;margin-top: 5px;" value="修改密码">
					<input class="btn" id="bt2" type="submit" style="width: 40%;margin-top: 5px;display: none;" value="确认修改密码" >
					<input class="btn" id="bta"  style="width: 40%;margin-top: 5px;" value="修改权限">
					<button class="btn" id="btb" style="width: 40%;margin-top: 5px;display: none;">确认修改权限</button>

				</td>
			</tr>
		</table>
	</div>
	<!-- 3.备用div -->
	<input type="hidden" name="" id="uid" value="<?php echo $this->value['adminInfo']['uid']; ?>">

</div>

<?php include TCACHE.'footer.html'; ?>	
</body>
</html>

<script type="text/javascript">
	$(function(){

		$('#bta').click(function(){
			$('#qx_div').show();
			$('#bt1').hide();
			$('#bta').hide();
			$('#btb').show();
		})

		$('#btb').click(function(){
			//获取所有选中的权限
			var ps = $("input[name='ps']:checked");
			var psstr = '';
			var url = 'index.php?app=admin&action=_ChangePermission';
			var uid = $('#uid').val()
			$.each(ps,function(i,item){
				psstr += $(item).val()+',';
			})
			$.post(url,{'psstr':psstr,'uid':uid},function(msg){
				alert(msg.msg);
				if(msg.state == 0) window.location.href = 'index.php?app=admin';
			},'json')
		})

		$('#bt1').click(function(){
			$('#old_div').show();
			$('#new_div').show();
			$('#bt2').show();
			$('#bta').hide();
			$('#bt1').hide();
		})

		$('#bt2').click(function(){
			var oldPwd = $('#oldPassWD').val()
			var newPassWD = $('#newPassWD').val()
			var url = 'index.php?app=admin&action=changepwd';
			var uid = '<?php echo $this->value['adminInfo']['uid']; ?>';
			if(!oldPwd || !newPassWD || !uid){
				alert('请填写相关数据');
				return;
			}
			$.post(url,{'oldpwd':oldPwd,'newpwd':newPassWD,'uid':uid},function(msg){
				if(msg.state ==0){
					alert(msg.msg);
					window.location.reload();
				}	
				if(msg.state ==1)	alert(msg.msg);
			},'json')
		})
	})
</script>