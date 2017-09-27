<?php

	return array(
		'url' =>array(
			'newsurl' => 'http://tech.qq.com/'
		),

		'preg' =>array(
			'titleclass' => '/<h3\sclass="f18 l26">.*?[\s\S].*?<a.*?href="(.*?)"\stitle="(.*)"/',
			'newsimg' => '~<img\sclass="zutu0" src=\'(.*?)\' />~',
			'all' => '~<img\sclass="zutu0" src=\'(.*?)\' />.*[\s\S].*[\s\S].*?<h3\sclass="f18 l26">.*?[\s\S].*?<a.*?href="(.*?)"\stitle="(.*)"~'
		),
	);