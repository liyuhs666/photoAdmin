<!DOCTYPE html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AdminPanel</title>
<?php include __PUBTPL__.'head.html'; ?>
<script type="text/javascript" src="<?php echo __JS__; ?>/jquery.main.js"></script>
<style type="text/css">
#box{
	margin-top: 0px;
	border-radius: 0px;
}
#tab2,#tab2 td{
	border: 0px solid #eee;
}
</style>
</head>
<body>
<?php include TCACHE.'nav.html'; ?>

	<div class="panel panel-info" id="box">
	    
	    <div class="panel-body">
	        
			<table class="table table-bordered">
			  <caption>菜单操作</caption>
			  <thead>
			    <tr>
			      <th>名称</th>
			      <th>字段</th>
			      <th>链接</th>
			      <th>操作</th>
			    </tr>
			  </thead>
			  <tbody>
			  	
		<?php if(count($this->value['menu'])>0){ ?>
		<?php foreach($this->value['menu'] as $this->value['m']){ ?>
			
				    <tr>
				      <td><?php echo $this->value['m']['name']; ?></td>
				      <td><?php echo $this->value['m']['nw_field']; ?></td>
				      <td><a href="<?php echo $this->value['m']['url']; ?>"><?php echo $this->value['m']['url']; ?></a></td>
				      <td><a href="index.php?app=admin&action=delete&id=<?php echo $this->value['m']['id']; ?>">删除</a></td>
				    </tr>
				<?php } ?><?php } ?>
				
			  </tbody>
			</table>
			<table class="table table-bordered" id="tab2">
				<form action="index.php?app=admin&action=adminadd"  role="form" method="post" class="form-control">
				    <tr>
				      <td><input name="name" id=""  class="form-control" placeholder="名称"></td>
				      <td><input name="nw_field" id="" class="form-control" placeholder="字段"></td>
				      <td><input name="url" id=""  class="form-control" placeholder="url"></td>
				      <td><input type="submit" name="submit" id="" value="添加"  class="form-control"></td>
				    </tr>
				</form>
			</table>
	    </div>
	</div>
<?php include TCACHE.'footer.html'; ?>	
</body>	
</html>
