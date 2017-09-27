<?php

	//系统日志首页
	 function SyslogIndex(){

		$sql = "SELECT * FROM {$this->conf['prefix']}syslog";
		$p = 1;

		if($p){
			$count = DB::DBGetValue("SELECT count(*) FROM {$this->conf['prefix']}syslog");
			isset($_GET['page']) ? $page = $_GET['page'] :$page = 1;
			$size = 5;	//每页数量
			$pagecount = ceil($count/$size);	//总页数
			$start = ($page-1)*$size;
			$sql .= " ORDER BY id DESC ";
			$sql .= " LIMIT $start,$size ";

			$page_string = '<ul class="pagination">';

			if($pagecount == 1){
				 $page_string .= '第一页|下一页|';

			}elseif($page == 1){
				$page_string .= '首页|';
				$page_string .= "<a href='index.php?app=syslog&page=".($page+1)."'>下一页</a>"."|";
				$page_string .= "<a href='index.php?app=syslog&page=$pagecount'>尾页</a>"."|";

			}elseif($page == $pagecount || $pagecount == 0){
				
				$page_string .= "<a href='index.php?app=syslog&page=1'>首页</a>"."|";
				$page_string .= "<a href='index.php?app=syslog&page=".($page-1)."'>上一页</a>"."|";
				$page_string .= '|尾页';

			}else{
				$page_string .= "<a href='index.php?app=syslog&page=1'>首页</a>"."|";
				$page_string .= "<a href='index.php?app=syslog&page=".($page-1)."'>上一页</a>"."|";
				$page_string .= "<a href='index.php?app=syslog&page=".($page+1)."'>下一页</a>"."|";
				$page_string .= "<a href='index.php?app=syslog&page=$pagecount'>尾页</a>"."|";
			}

			$page_string .= '</ul>';

		}

		$data = DB::DBGetAll($sql);
		$data = $this->dealData($data);

		// echo '<pre/>';print_r($count);exit(''); 

		$this->assign('list',$data);
		$this->assign('page',$page);

		if($count > $size){
			$this->assign('pagecode',$page_string);
			$this->assign('pagecount',$pagecount);
		}

		$this->display("syslog.html");
	}
