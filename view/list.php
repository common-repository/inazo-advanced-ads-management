<?php
if( !defined( 'ABSPATH' ) ){
	
	exit(-1);
}
?>
<!-- 
<ul class="subsubsub">
	<li class="all"><a class="current" href="edit.php?post_type=page">Tous <span class="count">(5)</span></a> |</li>
	<li class="publish"><a href="edit.php?post_status=publish&amp;post_type=page">Corbeille<span class="count">(5)</span></a></li>
</ul>
-->
<?php

$theList->prepare_items();
$theList->display();

?>
