1.src或者href中不要直接使用__XXX__常量,而是应该使用php标签输出
错误方式:
<script src="__JS__/jquery.js"></script>
正确方式:
<script src="<?php echo __JS__; ?>/jquery.js"></script>