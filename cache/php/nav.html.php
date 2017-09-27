<nav class="navbar navbar-inverse" role="navigation" style="width: 100%;border-radius: 0px;float: left;">

    <div class="collapse navbar-collapse" id="example-navbar-collapse" style="margin-left: 0;padding-left: 0px">
        <ul class="nav navbar-nav">

        	<!-- 遍历所有菜单 -->
            
		<?php if(count($this->value['menu'])>0){ ?>
		<?php foreach($this->value['menu'] as $this->value['m']){ ?>
			
            	<li style="border-right: 1px solid #333">
            		<a href="<?php echo $this->value['m']['url']; ?>" style="float: left;padding: 9px;">
                        <span id="icon_<?php echo $this->value['m']['id']; ?>" style="float: left;margin-bottom: 8px;"><?php echo $this->value['m']['icon']; ?></span>
                        <font style="font-size: 16px;margin-left: 3px;line-height: 1.6em;color:#eee;font-family:微软雅黑 "><?php echo $this->value['m']['nw_field']; ?></font>
                    </a>
            	</li>
                
            <?php } ?><?php } ?>

        </ul>
        <div id="admin_menu" style="float: right;height: 100%;vertical-align:middle;line-height: 3em;height: 3em">
            <?php if(!$this->session('_user')){ ?>
            <a style="color: #fff" href="index.php?app=admin&action=login"><span id="licon" style="float: left;margin-bottom: 8px;color: #fff;"><svg class="icon" style="width: 1em; height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="450"><path d="M998.4 820.8c-12.8 0-24-11.2-24-24 0-33.6-4.8-65.6-12.8-96-25.6-86.4-84.8-161.6-164.8-206.4l-27.2-14.4 19.2-22.4c32-38.4 48-84.8 48-134.4 0-116.8-96-212.8-216-212.8-12.8 0-24-11.2-24-24s11.2-24 24-24c145.6 0 264 116.8 264 260.8 0 51.2-14.4 100.8-43.2 142.4 80 52.8 139.2 131.2 164.8 222.4 9.6 35.2 16 72 16 108.8 0 12.8-11.2 24-24 24zM708.8 961.6H96c-51.2 0-94.4-41.6-94.4-91.2 0-134.4 68.8-257.6 180.8-331.2-27.2-41.6-43.2-92.8-43.2-142.4 0-144 118.4-260.8 264-260.8s264 116.8 264 260.8c0 51.2-14.4 100.8-43.2 142.4 112 73.6 180.8 196.8 180.8 329.6-1.6 51.2-43.2 92.8-96 92.8zM403.2 184c-118.4 0-216 96-216 212.8 0 49.6 17.6 96 48 134.4l19.2 22.4-25.6 14.4C118.4 628.8 51.2 745.6 51.2 868.8c0 24 20.8 43.2 46.4 43.2h612.8c25.6 0 46.4-19.2 46.4-43.2h24-24c0-124.8-68.8-240-179.2-302.4l-25.6-14.4 19.2-22.4c32-38.4 48-84.8 48-134.4-1.6-115.2-97.6-211.2-216-211.2z" fill="" p-id="451"></path></svg></span>Login</a>
            <?php }else{ ?>
                <font style="color: #fff">你好,</font>
                <a href="index.php?app=admin&action=AdminDetails&uid=<?php echo $this->value['_user']['uid']; ?>" id="user_n" style="color: #fff;"><?php echo $this->value['_user']['uname']; ?> </a>
                <a href="index.php?app=admin&action=logout&uid=<?php echo $this->value['_user']['uid']; ?>" id="user_o" style="color: #fff">退出</a>
            <?php } ?>
        </div>
    </div>
</nav>

<style type="text/css">
li span svg{
    width: 1.7em !important;
    height: 1.7em !important;
}
#licon svg{
    width: 1.5em !important;
    height: 1.5em !important;
}
body{
    background-color: #404040;
    margin: 0;
    padding: 0;
}
#user_n:hover,#user_o:hover{
    text-decoration: none;
    color: red !important;
}
</style>