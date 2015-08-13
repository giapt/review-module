<?php
	Yii::app()->clientScript->registerCss('ext-comment', "
	div.ext-comment {
		width: 100%;	
		min-height: 200px;
	}
	
	span.ext-comment-options {
		float: right;
		color: #aaa;
	}
	span.khung-left{
		margin-left :5px;
	}
	td.nav-td1{
		color: #cc3b1a;
	}
	
	p.test{
		color : black;
	}
	
	nav.khungnav{
		border-width:thin;
		width:560px;
		
	}
	");
?>
<div class="ext-comment" id="ext-comment-<?php echo $data->id; ?>">
	<b id = "commentpoint"><?php echo "<a href=".'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."#ext-comment-".$data->id.">";
			echo "#";
			echo "</a>";
	?></b><hr>
<div id = "atext" >
	<span class="ext-comment-head">
		
		wrote on 
		<span class="ext-comment-date">
			<?php echo Yii::app()->format->formatDateTime(
				is_numeric($data->createDate) ? $data->createDate : strtotime($data->createDate)
			); 
			?>
		</span>
		</span>
		<span class="ext-comment-options">
		<?php if (!Yii::app()->user->isGuest && (Yii::app()->user->id == $data->userId)) {
		    echo CHtml::ajaxLink('delete', array('/comment/comment/delete', 'id'=>$data->id), array(
				'success'=>'function(){ $("#ext-comment-'.$data->id.'").remove(); }',
			    'type'=>'POST',
		    ), array(
			    'id'=>'delete-comment-'.$data->id,
			    'confirm'=>'Are you sure you want to delete this item?',
		    ));
			echo " | ";
			echo CHtml::ajaxLink('edit', array('/comment/comment/update', 'id'=>$data->id), array(
				'replace'=>'#ext-comment-'.$data->id,
				'type'=>'GET',
			), array(
				'id'=>'ext-comment-edit-'.$data->id,
			));
		} ?>
		</span>


	


	
			<div id = "view_comment_1">
				<?php
				$model=new Comment;
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
					 		if ($c=='Rate1')
					 		{echo "Reputation";}
					 		if ($c=='Rate2')
					 		{echo "Location";}
					 	if ($c=='Rate3')
					 		{echo "Opportunities";}
					 	if ($c=='Rate4')
					 		{echo "Library";}
					 	if ($c=='Rate5')
					 		{echo "Internet";}
			     		$this->widget('CStarRating',array(
					    'name'=>'Comment['.$a.']',
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
			<div id = "view_comment_2" >
				<?php echo nl2br(CHtml::encode($data->message)); ?>
			</div>
	
			
	<br style="clear: both;"/>
</div>
</div>