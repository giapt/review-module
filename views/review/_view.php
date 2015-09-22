<?php
// $assets=Yii::app()->modules;
// $aa=$assets['review']['updateModelAuto'];
       $assetsurl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('review.assets') );

Yii::app()->clientScript->registerCssFile($assetsurl.'/css/teacher.css'); 
Yii::app()->clientScript->registerCssFile($assetsurl.'/css/school.css'); 
Yii::app()->clientScript->registerCssFile($assetsurl.'/css/index.css');
//Yii::app()->clientScript->registerCssFile($assetsurl.'/css/styles.css');
		// Yii::app()->clientScript->registerCss($assetsurl.'/css/styles.css');
// echo "<pre>";
// echo var_dump($aa);
// echo "</pre>";
// die();
?>

<div class="ext-review" id="ext-review-<?php echo $data->id; ?>">
	<b id = "reviewpoint"><?php echo "<a href=".'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."#ext-review-".$data->id.">";
			echo "#";
			echo "</a>";
	?></b>
	<hr>

<div id = "atext" >
	<span class="ext-review-head">
		
		wrote on 
		<span class="ext-review-date">
			<?php echo Yii::app()->format->formatDateTime(
				is_numeric($data->createDate) ? $data->createDate : strtotime($data->createDate)
			); 
			//echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			
								
	
			?>
		</span>
		</span>
		<span class="ext-review-options">
		<?php if (!Yii::app()->user->isGuest && (Yii::app()->user->id == $data->userId)) {
		    echo CHtml::ajaxLink('delete', array('/review/review/delete', 'id'=>$data->id), array(
				'success'=>'function(){ $("#ext-review-'.$data->id.'").remove(); }',
			    'type'=>'POST',
		    ), array(
			    'id'=>'delete-review-'.$data->id,
			    'confirm'=>'Are you sure you want to delete this item?',
		    ));
			echo " | ";
			echo CHtml::ajaxLink('edit', array('/review/review/update', 'id'=>$data->id), array(
				'replace'=>'#ext-review-'.$data->id,
				'type'=>'GET',
			), array(
				'id'=>'ext-review-edit-'.$data->id,
			));
		} ?>
		</span>


			<div id = "view_review_1">
				<?php
				$model=new Review;
				$a=$data->id;
				$colArr=$model->attributes;		
			    for ($i = 0; $i <  count($colArr); $i++) {
			    	
			    	echo "&nbsp &nbsp ";
				    $key=key($colArr);
				    //die();
				    $val=$colArr[$key];
					next($colArr);
			     	if (($key<> 'average') and($key<>'id') and ($key<>'message') 
			     		and ($key<>'userId') and ($key<>'createDate') ) {
			     	 	$a=$a+100;
			     	 	$c=$data->getAttributeLabel($key);
					 		// 
			     	 	echo $c;
							     
			  
			     		$this->widget('CStarRating',array(
					    'name'=>'Review['.$a.']',
					    'value'=>$data->$key,
					    'minRating'=>1, //minimal value
						'maxRating'=>5,//max value
						'starCount'=>5, //number of stars
					    'readOnly'=>true,
					));	  
					// echo $a;   
				 		}
				     echo "<br>";
				     echo "<br>";
				  
			    }
				?>
			</div>
			<div id = "view_review_2" >
				<?php echo nl2br(CHtml::encode($data->message)); ?>
			</div>
	
			
	<br style="clear: both;"/>
</div>

</div>
