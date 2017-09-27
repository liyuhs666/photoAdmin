<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>列表</title>
	<?php include __PUBTPL__.'head.html'; ?>
</head>
<style type="text/css">
#list div {margin:20px;}  
#list{  
	list-style-type: none;  
	width: 1000px;  
}  
#list h3{font: bold 20px/1.5 Helvetica, Verdana, sans-serif;}  
#list li img{  
	width: 120px;
	float: left;  
	margin: 0 15px 0 0;  
}  
#list li p{ 
	font: 200 12px/1.5 Georgia, Times New Roman, serif;
}  
#list li{  
	padding: 10px;  
	overflow: auto;  
}  
#list li:hover {  
	background: #111;  
	cursor: pointer;  
}
#box{
	margin: 0px 200px;
	background-color: #202020;
}
#add_log{
	color: #fff;
}
h5 a{
	color:#BCEE68 !important;
}
a{text-decoration :none;}
a:hover {text-decoration: none;}

#pages{color: #888;margin-bottom: 10px;}
</style>
<body>
<?php include TCACHE.'nav.html'; ?>
<div class="" style="text-align: right;float: right;margin-right: 10px;">
	<a href="index.php?app=syslog&action=showadd" class="btn  btn-success" id="add_log">添加日志</a>
	<a href="index.php?app=syslog&action=SetMonth" class="btn  btn-success" id="add_log">设置月份</a>
	<br><br>
	<select name=""  class="form-control" id="monthSelect">
		<?php if($this->session('now_month')){ ?>
		<option value="0"><?php echo $this->value['now_month']; ?></option>
		<?php }else{ ?>
		<option value="0">月份选择</option>
		<?php } ?>
		
		<?php if(count($this->value['months'])>0){ ?>
		<?php foreach($this->value['months'] as $this->value['month']){ ?>
			
			<option value="<?php echo $this->value['month']['month']; ?>"><?php echo $this->value['month']['time']; ?>(<?php echo $this->value['month']['count']; ?>篇)</option>
		<?php } ?><?php } ?>
	</select>
</div>
			
<div id="box"  >
	
		<?php if(count($this->value['list'])>0){ ?>
		<?php foreach($this->value['list'] as $this->value['v']){ ?>
			
	<ul id="list">  
		<li>  
			<img src="<?php echo $this->value['v']['img']; ?>" />  
			<h5><a href="index.php?app=syslog&action=detail&id=<?php echo $this->value['v']['id']; ?>">
			<span style="color: #fff">【<?php echo $this->value['v']['time']; ?>】</span>
			<?php echo $this->value['v']['title']; ?>
			</a></h5>  
			<p>
				<font style="color:#aa9">ID:<?php echo $this->value['v']['id']; ?> &nbsp;&nbsp;&nbsp; 浏览数:<?php echo $this->value['v']['views']; ?> &nbsp;&nbsp;&nbsp; 标签:<?php echo $this->value['v']['tag']; ?>&nbsp;&nbsp;&nbsp;分类:<?php echo $this->value['v']['typename']; ?></font>
			</p>  
		</li>  
	</ul> 
	<?php } ?><?php } ?>
	
	<?php if($this->session('pagecount')){ ?>
	<div style="text-align: center;background-color: #202020">
		<?php echo $this->value['pagecode']; ?>
	  	<p id="pages">共<?php echo $this->value['pagecount']; ?>页,当前第<?php echo $this->value['page']; ?>页,一共有<?php echo $this->value['count']; ?>篇日志</p>
	</div>
	<?php } ?>

</div> 

<br><br>
<?php include TCACHE.'footer.html'; ?>	
</body>
</html>
<script type="text/javascript">
	$(function(){
		$('#monthSelect').change(function(){
			var month = $(this).val()
			var url = "index.php?app=syslog&month="+month;
			window.location.href = url;
		})
	})
</script>

