<?php
class Webinfos_Welcome {
	public function __construct () {
		add_action('wp_dashboard_setup', array($this, 'custom_welcome'));
	}
	
	function custom_welcome_content () {
		global $wpdb;
		$id=get_option('active_msg');
		$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}webinfos WHERE id ='$id'");
		?>
			<?php
			if ($row->imgurl != "") {
				$img=getimagesize (plugin_dir_url(__FILE__).'img/'.$row->imgurl);
				$dwimg=$img[0];
				$dhimg=$img[1];
				if ($img[0]>'400') {
					$wp = ($dwimg/100);
					while ($img[0]>'400') {
						$img[0]= $img[0]-$wp;
					}
					$img[1]= ($dhimg/$dwimg)*$img[0];
				}
				if ($img[1]>'400') {
					$hp = ($dhimg/100);
					while ($img[1]>'400') {
						$img[1]= $img[1]-$hp;
					}
					$img[0]= $img[1]/($dhimg/$dwimg);
				}
				if ($row->website != "") {
				?>
				<a href="<?php echo $row->website; ?>"><img src="<?php echo plugin_dir_url(__FILE__).'img/'.$row->imgurl; ?>" height="<?php echo $img[1].'px'; ?>" width:"<?php echo $img[0].'px'; ?>" alt="<?php echo $row->imgalt; ?>"></a>
				<?php
				}
				else {
				?>
				<img src="<?php echo plugin_dir_url(__FILE__).'img/'.$row->imgurl; ?>" height="<?php echo $img[1].'px'; ?>" width:"<?php echo $img[0].'px'; ?>" alt="<?php echo $row->imgalt; ?>">
				<?php
				}
				?>
			<?php
			}
			?>
			<div><?php echo $row->msgtxt; ?></div>
			<?php
			if ($row->attachment != "") {
			?>
			<p> 
			<?php
			_e('Attachments : ', 'webinfos');
			?>
			<br>
			<?php
			$filelist=explode(',' , $row->attachment);
			$nb=0;
			for ($i=0; $i<sizeof($filelist); $i++) {
				$nb++;
				?>
				<span>
				<a href="<?php echo plugin_dir_url(__FILE__).'attachment/'.$filelist[$i]; ?>"><?php echo $filelist[$i] ?></a>
				<?php 
				if ($i+1 != sizeof($filelist))  {
					echo ' | ';	
				}	
				?>
				</span>
				<?php
				if ($nb==3) {
				?>
				<br>
				<?php	
					$nb=0;
				}
			}
			?>
			</p>
			<?php
			}
			?>
			<?php
			if ($row->video != null && $row->video != "") {
			?>
			<video width="auto" controls>
  				<source src="<?php echo plugin_dir_url(__FILE__).'video/'.$row->video; ?>" type="video/mp4">
  				<?php _e('Your browser does not support HTML5 video.', 'webinfos'); ?>
			</video>
			<?php
			}
			?>
			<?php
			if ($row->contact=='1') {
			?>
				<div>
				<div><p style="font-size:14px;"><b><?php _e('Contact', 'webinfos'); ?></b></p>
				<?php
				if ($row->contactimgurl != "") {
					$photo=getimagesize (plugin_dir_url(__FILE__).'img/'.$row->contactimgurl);
					$dwimg=$photo[0];
					$dhimg=$photo[1];
					if ($photo[0]>'100') {
						$wp = ($dwimg/100);
						while ($photo[0]>'100') {
							$photo[0]= $photo[0]-$wp;
						}
						$photo[1]= ($dhimg/$dwimg)*$photo[0];
					}
					if ($photo[1]>'100') {
						$hp = ($dhimg/100);
						while ($photo[1]>'100') {
							$photo[1]= $photo[1]-$hp;
						}
						$photo[0]= $photo[1]/($dhimg/$dwimg);
					}
				?>
				<img src="<?php echo plugin_dir_url(__FILE__).'img/'.$row->contactimgurl; ?>" height="<?php echo $photo[1].'px'; ?>" width="<?php echo $photo[0].'px'; ?>" alt="<?php echo $row->contactimgalt; ?>" >
				<?php
				}
				?>
				<?php
				if ($row->tel != "") {
				?>
				<p style="margin-bottom:0px;"><u><?php _e('Phone : ', 'webinfos'); ?></u> <?php echo $row->tel; ?></p>
				<?php
				}
				?>
				<?php 
				if ($row->email != "") {
				?>
				<p style="margin-top:0px;"><u><?php _e('Email : ', 'webinfos'); ?></u> <a href="<?php echo 'mailto:'.$row->email; ?>"><?php echo $row->email; ?></a></p></div>
				<?php 
				}
				?>
				</div>
				<?php
			}
			?>
		
		<?php
	}

	function custom_welcome () {
		global $wpdb;
		if (get_option('active_msg')!= "0" && get_option('active_msg')!= "" && get_option('active_msg')!== null) {
			$id=get_option('active_msg');
			$row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}webinfos WHERE id ='$id'");
			$webinfos_title=$row->title;
		}
		else {
			$webinfos_title="Undefined title";
		}	
		wp_add_dashboard_widget(
		'custom_welcome_widget',
		$webinfos_title,
		array($this, 'custom_welcome_content')
		);
	
	}
}
?>