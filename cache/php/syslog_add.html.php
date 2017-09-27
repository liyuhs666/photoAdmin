<!DOCTYPE html>
<html>
<head>	
<?php include __PUBTPL__.'head.html'; ?>
<title>添加日志</title>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width">

<link type="text/css" rel="stylesheet" href="<?php echo __PUB__ ?>/css/jq22.css"  />
<link rel="stylesheet" href="<?php echo __EXT__ ?>/Udit/redactor/css/redactor.css" />

<script type="text/javascript" src="<?php echo __EXT__ ?>/Udit/lib/jquery-1.7.min.js"></script>	
<script src="<?php echo __EXT__ ?>/Udit/redactor/redactor.js"></script>

<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"> -->
<link href="<?php echo __EXT__ ?>/fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo __EXT__ ?>/fileinput/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<script src="<?php echo __EXT__ ?>/fileinput/js/plugins/sortable.js" type="text/javascript"></script>
<script src="<?php echo __EXT__ ?>/fileinput/js/fileinput.js" type="text/javascript"></script>
<script src="<?php echo __EXT__ ?>/fileinput/js/locales/fr.js" type="text/javascript"></script>
<script src="<?php echo __EXT__ ?>/fileinput/js/locales/es.js" type="text/javascript"></script>
<script src="<?php echo __EXT__ ?>/fileinput/themes/explorer/theme.js" type="text/javascript"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script> -->

<script type="text/javascript">
$(document).ready(
	function(){
		$('#redactor_content').redactor();
	}
);
</script>
<script type="text/javascript">
	function RemoveiFrame(){
		var htmlObj = $('.redactor_frame').contents().find('#page')
		var content = htmlObj.html()
		var key = $('#encode_key').val();
		var title = $('#title').val();
		var tags  = getTagContent();
		var filepath = $('#filepath').val()
		var logtype = $('#logtype').val()

		if(key.length != 16 && key.length != 4){
			alert('加密钥匙长度必须为16位');
			return false;
		}
		var data = {'content':content,'key':key,'title':title,'tags':tags,'filepath':filepath,'logtypes':logtypes}
		$.post('index.php?app=syslog&action=_addlog',data,function(msg){
			if(msg.state ==1) alert('添加成功')
			if(msg.state ==0) alert('添加失败,内容过多')
			window.location = 'index.php?app=syslog'
		},'json')
	}

	function getTagContent(){
		var tags = ''
		$('.tag_content').each(function(){
			var str = $(this).html()
			var res = str.match(/<span>(.*?)<\/span>/)
			tags += res[1] + ','
		})
		return tags;
	}
</script>
<style>
#Content-box{
	margin:10px 200px;
}
#caozuo,#key{width: 100%;text-align: center;}

</style>
</head>
<body>
<?php include TCACHE.'nav.html'; ?>

<!-- 1.标题 -->
<div id="title_div">
	<input type="text" class="form-control center-block" id="title" placeholder="标题" style="width: 72%;" >
</div>
<br>

<!-- 2.在线编辑器:content -->
<div id="Content-box">
	<textarea id="redactor_content" name="content" style="height: 360px;"><p>Hello and Welcome</p></textarea>
</div>

<table class="table" style="width: 1200px; border-top:1px solid #369 ;margin:20px auto;">
	<tr>
		<td style="width: 70%;">
			<!-- 4.图片上传 -->
			<form  enctype="multipart/form-data" style="width: 80%;margin:15px auto" method="post">
			    <div class="form-group">
			        <input id="file-1" type="file" multiple class="file" data-overwrite-initial="false" placeholder="" data-min-file-count="1">
			    </div>
			</form>
		</td>


		<td style="width: 30%;text-align: center;">
			<!-- 3.加密钥匙 -->
			<div id="key" style="margin:20px auto";>
				<input type="text" class="form-control center-block" id="encode_key" placeholder="加密钥匙" style="width: 100%;" >
			</div>

			<div id="typediv">
				<select  class="form-control" id="logtype" style="margin-bottom: 20px">
					<option value="0">类型选择</option>
					
		<?php if(count($this->value['logtypes'])>0){ ?>
		<?php foreach($this->value['logtypes'] as $this->value['t']){ ?>
			
						<option value="<?php echo $this->value['t']['id']; ?>"><?php echo $this->value['t']['typename']; ?></option>
					<?php } ?><?php } ?>
				</select>
			</div>

			<!-- 5.打标签 -->
			<div id="tag_box">
				
				<div class="demo" class="col-md-12" >
					<div class="plus-tag tagbtn clearfix" id="myTags"></div>
					<div class="plus-tag-add">
						<form id="" action="" class="login" >
							<div class="Form FancyForm" >
								<table style="margin:0px auto;">
									<tr>
										<td><input id="" name="" type="text" class="stext" maxlength="20" style="width: 380px;" /></td>
										<td style="text-align: left;" nowrap>
										<button type="button" class="Button RedButton Button18" style="font-size:16px;margin-left: 10px">打标签</button></td>
									</tr>
								</table>
							</div>
						</form>
					</div><!--plus-tag-add end-->
					
				</div>
			</div>
		</td>

	</tr>
</table>




<input type="hidden" id="filepath" >

<div id="caozuo" >
	<button onclick="RemoveiFrame()" id="getContent" class="btn-success btn-lg" style="width: 150px;margin-left:200px">提交</button>	
</div>	
<?php include TCACHE.'footer.html'; ?>	
</body>
</html> 

<script src="<?php echo __PUB__ ?>/js/jq22.js"></script>

<script>

    $("#file-1").fileinput({
        uploadUrl: 'index.php?app=syslog&action=upLoadImg', // you must set a valid URL here else you will get an error
        allowedFileExtensions: ['jpg', 'png', 'gif'],
        overwriteInitial: false,
        maxFileSize: 1000,
        maxFilesNum: 10,
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
    });

    $(document).ready(function () {
        $("#test-upload").fileinput({
            'showPreview': false,
            'allowedFileExtensions': ['jpg', 'png', 'gif'],
            'elErrorContainer': '#errorBlock'
        });
  
    });
</script>
<script type="text/javascript">
	$(function(){
		//这个函数就是应该这么写的,后台返回值在data.response.path中
		$('#file-1').on("fileuploaded", function (event, data, previewId, index) {
               var path = data.response.path;
               $('#filepath').val(path)
            });
	})
</script>
