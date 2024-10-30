<?php
if( !defined( 'ABSPATH' ) ){
	
	exit(-1);
}

echo $args['before_widget'];
			
			if ( ! empty( $instance['title'] ) ) {

				echo '<div id="'.$args['widget_id'].'" class="container-iam" style="width:'.$tailleW.'px; height:'.($tailleH+40).'px; margin:0 auto;">';
			
				echo '<div class="title">'.__( $instance['title'], 'inazo-adv-ads-manager' ).'</div>';
				
				$i = 0;
							
				foreach( $theAdds as $aAdds ){
					
					echo '
					<div class="inazo_adds_'.$args['widget_id'].'_'.$i.' addsToShow" style="width:'.$tailleW.'px; height:'.$tailleH.'px; ';
					
					if( $i == 0 )
						echo ' display:block; ';
					
					echo '">';
					
					
					if( !empty($aAdds['url_desti']) ){
						
						echo '<a href="'.$aAdds['url_desti'].'"';
						
						if( $aAdds['target_link'] == 1 )
							echo ' target="_blank" ';
						
						echo '>';
					}
					
					if( !empty($aAdds['id_media_use']) )
						echo wp_get_attachment_image( $aAdds['id_media_use'], array(0 => $tailleW,1 =>  $tailleH) );
					
					else
						echo stripslashes($aAdds['code_adds']);
					
					if( !empty($aAdds['url_desti']) ){
						
						echo '</a>';
					}
						
					echo '</div>';
					
					$i++;
				}
				
				
				echo '
					<input type="hidden" id="inazo_adds_current_'.$args['widget_id'].'" value="0" />
					<input type="hidden" id="inazo_adds_maximum_'.$args['widget_id'].'" value="'.($i - 1 ).'" />
				</div>';
				
				if( $multi == 0 ){
					
					echo '
					<input type="hidden" class="inazo_ads_multi_need" value="'.$args['widget_id'].'"/>
					';
				}
				
				//echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
			
			
			
			echo $args['after_widget'];
			
			?>