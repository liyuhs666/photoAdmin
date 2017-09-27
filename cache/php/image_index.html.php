<!DOCTYPE html>
<html class="no-js" lang="en"> 
<head>
    <title>Genius, Responsive HTML5 Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="templatemo">
    <meta charset="UTF-8">

    <link href='http://fonts.googleapis.com/css?family=Dosis:300,400,500,600,700,800' rel='stylesheet' type='text/css'>
        
    <!-- CSS Bootstrap & Custom -->
    <link href="<?php echo __CSS__; ?>/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo __CSS__; ?>/image_app/font-awesome.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="<?php echo __CSS__; ?>/image_app/templatemo_misc.css">
    <link rel="stylesheet" href="<?php echo __CSS__; ?>/image_app/animate.css">
    <link href="<?php echo __CSS__; ?>/image_app/templatemo_style.css" rel="stylesheet" media="screen">
        
    <!-- Favicons -->
    <link rel="shortcut icon" href="<?php echo __IMG__; ?>/ico/favicon.ico">
    
    <script src="<?php echo __JS__; ?>/jquery-1.10.2.min.js"></script>
    <script src="<?php echo __JS__; ?>/image_app/modernizr.js"></script>


    <link type="text/css" rel="stylesheet" href="<?php echo __PUB__ ?>/css/jq22.css"  />
	<link rel="stylesheet" href="<?php echo __EXT__ ?>/Udit/redactor/css/redactor.css" />

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

<style type="text/css">
.bg-image { position: fixed; top: 0; left: 0; background-image: url(<?php echo __IMG__; ?>/bg-homepage.jpg); background-repeat: no-repeat; background-attachment: fixed; background-size: cover; width: 100%; height: 100%; z-index: 8; }
.content{    background-color: #222222;}
#title_inp{    width: 20%;margin-top: 20px;}
#add_div{display: none;padding: 100px auto;text-align: center;}
#description{ margin:10px auto; width: 50%; }
#create_now{ color: #101010;margin:5px auto; }
</style>
</head>
<body>
    
    <div class="bg-image"></div>
    <div class="overlay-bg"></div>
    
	
    <div class="main-content">
        <div class="container" style="width: 90%">
            <div class="row">

                <!-- Static Menu JS控制文件是:public/js/image_app/emplatemo_custom.js:-->
                <div class="col-md-2 visible-md visible-lg" style="margin-right: 0px">
                    <div class="main_menu">
                        <ul class="menu">
                            <li><a class="show-3" href="#" data-toggle="tooltip" data-original-title="查看">浏览</a></li>
                            <li><a class="show-4" href="#" data-toggle="tooltip" data-original-title="相册管理">管理</a></li>
                            <li><a class="show-5" id="upload" href="#" data-toggle="tooltip" data-original-title="上传">上传</a></li>
                            <!-- class="show-5"等是js控制的,后面的是控制css的  -->
                            <li><a href="index.php?app=index" data-toggle="tooltip" data-original-title="首页">首页</a></li>
                        </ul>
                    </div> <!-- /.main-menu -->
                </div> <!-- /.col-md-2 -->

                <!-- Begin Content -->
                <div class="col-md-10" >


                    <div id="menu-container">
						
                        <!-- 图片展示模板 -->
                        <div id="menu-3" class="content our-gallery imgcontent">
                            <div class="3ontent-inner">
								
								<!-- 控制展示数量的按钮 -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="widget-title">相册<?php echo $this->value['albumName']; ?></h3>
                                    </div> <!-- /.col-md-6 -->
                                    <div class="col-md-6">
                                        <div class="filter-work pull-right">
                                            <a href="#nogo" class="toggle-filter">Category Filter</a>
                                            <ul class="filter-controls controls">
                                                <li class="filter" data-filter="all">Show All</li>
                                                <li class="filter" data-filter="branding">Branding</li>
                                                <li class="filter" data-filter="graphic">Graphic Design</li>
                                                <li class="filter" data-filter="nature">Nature</li>
                                            </ul>
                                        </div> <!-- /.filter-work -->
                                    </div> <!-- /.col-md-6 -->
                                </div> <!-- /.row -->

                                <div class="row">
                                    <div id="Grid">


										
		<?php if(count($this->value['imgs'])>0){ ?>
		<?php foreach($this->value['imgs'] as $this->value['img']){ ?>
			
                                        <!-- .col-md-4(一行3个,md-3一行4个) -->
                                        <div class="col-md-3 col-sm-6 mix graphic" style="width: 25%">  <!--标签 tags--> 
                                            <div class="gallery-item">
                                                <div class="gallery-thumb">
                                                    <img src="<?php echo $this->value['img']['path']; ?>" alt="">
                                                    <div class="overlay-g">
                                                        <a href="<?php echo $this->value['img']['path']; ?>" data-rel="lightbox" class="fa fa-search"></a>
                                                    </div>
                                                </div> <!-- /.gallery-thumb -->
                                                <div class="gallery-content">
                                                    <h4 class="gallery-title" style="background-color: #505050;color: #fff;font-size: 14px;line-height: 20px;font-family:微软雅黑" imgid="title_<?php echo $this->value['img']['id']; ?>" onclick="ChangeTitle(this)">
                                                        <?php echo $this->value['img']['title']; ?> &nbsp; &nbsp; &nbsp;
                                                        <?php echo $this->value['img']['time']; ?> &nbsp;
                                                        <a href="index.php?app=image&action=imgdelete&id=<?php echo $this->value['img']['id']; ?>&page=<?php echo $this->value['page']; ?>" style="color: #fff">删除</a>
                                                    </h4>     <!--标题 title--> 
                                                    <span class="gallery-category">
                                                       
                                                        
                                                    </span>  <!--记录 remark--> 
                                                </div> <!-- /.gallery-content -->
                                            </div> <!-- /.gallery-item -->
                                        </div> <!-- /.col-md-4 -->
										<?php } ?><?php } ?>


                                    </div> <!-- /#Grid -->
                                </div> <!-- /.row -->
                            </div> <!-- /.content-inner -->

                            <?php if($this->session('pagecount')){ ?>
							<div style="text-align: center;background-color: #202020">
								<?php echo $this->value['pagecode']; ?>
							  	<p id="pages">总共<?php echo $this->value['pagecount']; ?>页,当前第<?php echo $this->value['page']; ?>页</p>
							</div>
							<?php } ?>

                        </div> <!-- /.our-gallery -->

                        <!-- 4.相册模块 -->
                        <div id="menu-4" class="content our-gallery" >
                            <div id="album_div">
                                <div id="caozuo_div" style="text-align: center;line-height: 150px">
                                    <button class="btn-lg" value="创建图册" style=";color: #101010" onclick="create_album()">创建图册</button>
                                </div>
                                
                                <div id="album_list">
                                    
		<?php if(count($this->value['albumlist'])>0){ ?>
		<?php foreach($this->value['albumlist'] as $this->value['l']){ ?>
			
                                    <div class="col-md-4 col-sm-6 mix graphic">  <!--标签 tags--> 
                                        <div class="gallery-item">
                                            <div class="gallery-thumb">
                                                <img src="<?php echo $this->value['l']['coverphoto']; ?>" alt="">
                                                <div class="overlay-g">
                                                    <a href="<?php echo $this->value['l']['coverphoto']; ?>" data-rel="lightbox" class="fa fa-search"></a>
                                                </div>
                                            </div> <!-- /.gallery-thumb -->
                                            <div class="gallery-content">
                                                <a  style="font-size: 16px;line-height: 24px" href="index.php?app=image&albumid=<?php echo $this->value['l']['id']; ?>" >
                                                  【<?php echo $this->value['l']['id']; ?>】<?php echo $this->value['l']['name']; ?>
                                                </a>
                                                 <br>   <!--标题 title--> 
                                                <span class="gallery-category" style="margin-left: 200px">
                                                    <?php echo $this->value['l']['ctime']; ?> &nbsp;
                                                    <a href="index.php?app=image&action=albumdelete&id=<?php echo $this->value['l']['id']; ?>" style="color: #000">删除</a>
                                                    <!-- <a href="index.php?app=image&action=imgdelete&id=<?php echo $this->value['l']['id']; ?>&page=<?php echo $this->value['page']; ?>">删除</a> -->
                                                </span>  <!--记录 remark--> 
                                            </div> <!-- /.gallery-content -->
                                        </div> <!-- /.gallery-item -->
                                    </div> <!-- /.col-md-4 -->
                                    <?php } ?><?php } ?>
                                </div>

                                <div id="album_list_div">
                                    
                                </div>

                                <div id="add_div" >
                                    <input type="text" class="form-control center-block" id="title_inp" placeholder="标题" >
                                    <textarea class="form-control" rows="3" id="description"></textarea>
                                    <div id="album_upload_div">
                                        <form  enctype="multipart/form-data" style="margin:15px auto;width:50% !important;" method="post">
                                            <div class="form-group" style="margin:20px auto;">
                                                <input id="file-2" type="file" multiple class="file" data-overwrite-initial="false" placeholder="" data-min-file-count="1">
                                            </div>
                                        </form>
                                    </div>
                                    <button class="btn" id="create_now"  onclick="Ajax_create()">创建相册</button>
                                </div>
                                
                                <input type="hidden" id="album_cover_path" >

                            </div>
                        </div>

                        <!-- 上传模块 -->
                    	<div id="menu-5" class="content our-gallery" style="margin-top: 100px;" >
							<div id="upload_div">
                                <select class="form-control" name="image_upload_album" id="album_id">
                                  <option>选择相册</option>
                                  
		<?php if(count($this->value['albumlist'])>0){ ?>
		<?php foreach($this->value['albumlist'] as $this->value['g']){ ?>
			
                                    <option value="<?php echo $this->value['g']['id']; ?>">【<?php echo $this->value['g']['id']; ?>】<?php echo $this->value['g']['name']; ?></option>
                                  <?php } ?><?php } ?>
                                </select>
								<form  enctype="multipart/form-data" style="margin:15px auto" method="post">
								    <div class="form-group" style="margin:20px auto;">
								        <input id="file-1" type="file" multiple class="file" data-overwrite-initial="false" placeholder="" data-min-file-count="1">
								    </div>
								</form>
							</div>
                    	</div>
						<input type="hidden" id="filepath" >
						
                        <?php include TCACHE.'footer.html'; ?>	
                
                </div> <!-- /.col-md-10 -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </div> <!-- /.main-content -->

    <script src="<?php echo __JS__; ?>/bootstrap.min.js"></script>
    <script src="<?php echo __JS__; ?>/image_app/jquery.mixitup.min.js"></script>
    <script src="<?php echo __JS__; ?>/image_app/jquery.nicescroll.min.js"></script>
    <script src="<?php echo __JS__; ?>/image_app/jquery.lightbox.js"></script>
    <script src="<?php echo __JS__; ?>/image_app/templatemo_custom.js"></script>

    <!-- 图片上传所用js,css -->
	<script src="<?php echo __PUB__ ?>/js/jq22_10.js"></script>
	
	<script type="text/javascript">

   
        function Ajax_create(){
            var name = $('#title_inp').val()
            var description = $('#description').val()
            var coverphoto = $('#album_cover_path').val()
            var data = {
                'name':name,
                'description':description,
                'coverphoto':coverphoto
            }

            $.post('index.php?app=image&action=CreateAlbum',data,function(msg){
                if(msg.state==0){
                    window.location = 'index.php?app=image'
                }
            },'json')
        }

        //创建相册
        function create_album(){
            $('#add_div').show();
            $('#caozuo_div').hide();
            $('#album_list').hide();
            $('#caozuo_div').css('line-height',0);
        }

		function ChangeTitle(e){
			var title_id = $(e).attr('imgid')
			var id =  title_id.substr(title_id.indexOf('_')+1)
			var inp = $("<input type='text' style='color:#505050' onblur='ChangeTitleSure("+id+")' id='inp_"+id+"' >")
			$(e).parent().html(inp)
		}

		function ChangeTitleSure(id){
			var inpOjb = $('#inp_'+id)
			var value = inpOjb.val()
			var url = 'index.php?app=image&action=ChangeTitle';
			$.post(url,{'value':value,'id':id},function(msg){
				if(msg.state !=0){
					 alert(msg.msg)
					}else{
						var h4 = '<h4 class="gallery-title" style="background-color: #505050;color: #fff;font-size: 15px;line-height: 20px;font-family:微软雅黑" imgid="title_'+id+'" onclick="ChangeTitle(this)">'+value+'</h4>';
						inpOjb.parent().html(h4)
					}
				
			},'json')
		}
	</script>
    <script>
        function initialize() {
          var mapOptions = {
            zoom: 15,
            center: new google.maps.LatLng(16.832179,96.134976)
          };

          var map = new google.maps.Map(document.getElementById('map-canvas'),
              mapOptions);
        }

        function loadScript() {
          var script = document.createElement('script');
          script.type = 'text/javascript';
          script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
              'callback=initialize';
          document.body.appendChild(script);
        }

    </script>

</body>
</html>

<script>
    var batch;
    $("#file-1").fileinput({
        uploadUrl: 'index.php?app=image&action=upLoadImg', // you must set a valid URL here else you will get an error
        allowedFileExtensions: ['jpg', 'png', 'gif'],
        overwriteInitial: false,
        maxFileSize: 1000,
        maxFilesNum: 10,
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        },
        uploadExtraData:function(){
            var bid = $('#album_id').val()
            return {'ChinaNo1':bid};
        }
    });

    $("#file-2").fileinput({
        uploadUrl: 'index.php?app=image&action=upLoadAlbumCover', // you must set a valid URL here else you will get an error
        allowedFileExtensions: ['jpg', 'png', 'gif'],
        overwriteInitial: false,
        maxFileSize: 1000,
        maxFilesNum: 10,
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function (filename) {
            return filename.replace('(', '_').replace(']', '_');
        },

    });


    $(document).ready(function () {
        $('.imgcontent').show()
        $("#file-1").fileinput({
            'showPreview': true,
            'allowedFileExtensions': ['jpg', 'png', 'gif'],
            'elErrorContainer': '#errorBlock'
        });

        // 此处导致无法刷新出选择框
        // if(<?php echo $this->value['page']; ?> != 1 || <?php echo $this->value['albumid']; ?> ){
        //     $('.imgcontent').show()
        // }
  
    });
</script>
<script type="text/javascript">
	$(function(){
		//这个函数就是应该这么写的,后台返回值在data.response.path中
		$('#file-1').on("fileuploaded", function (event, data, previewId, index) {
			   // alert('上传中...')
               var path = data.response.path;
               $('#filepath').val(path)
            });

        $('#file-2').on("fileuploaded", function (event, data, previewId, index) {
               // alert('上传中...')
               var path = data.response.path;
               $('#album_cover_path').val(path)
            });
        
	})
</script>
