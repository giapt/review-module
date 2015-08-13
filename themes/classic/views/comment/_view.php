<?php
	Yii::app()->clientScript->registerCss('ext-comment', "
	div.ext-comment {
		width: 100%;
		margin: 25px auto;
		min-height: 100px;
	}
	div.ext-comment p {
		padding-left: 125px;
	}
	div.ext-comment hr {
		margin: 0;
		padding: 0;
		border: none;
		border-bottom: solid 1px #aaa;
	}
	div.ext-comment img {
		float: left;
		width: 80px;
		height: 80px;
	}
	span.ext-comment-name {
		font-weight: bold;
	}
	span.ext-comment-head {
		color: #aaa;
	}
	span.ext-comment-options {
		float: right;
		color: #aaa;
	}
	#canle{
padding-left:5px;
}
	");
?>
<div class="ext-comment" id="ext-comment-<?php echo $data->id; ?>">
	<hr />
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
		<table id = "canle">
			<tr>
				<td>
					<?php
					$model=new Comment;
					$a='';
					$colArr=$model->attributes;

					$thuoctinh1 =array("von hieu biet","a","b","c");//mangr cuar t ddaay
					echo "<p>";
					$j =0;
				    for ($i = 0; $i <  count($colArr); $i++) {

				    	

				    	$a=$a+$data->id;
					    $key=key($colArr);
					    //die();
					    $val=$colArr[$key];
						next($colArr);
				     	if (($key<> 'average') and($key<>'id') and ($key<>'message') and ($key<>'userId')
				     	 and ($key<>'createDate') ) {
				     		if($j<4){
				    		echo  $thuoctinh1[$j];	
				    		$j = $j+1;
				    	}
				     		//die();
				     		$this->widget('CStarRating',array(
							    'name'=>'Comment['.$a.']',
							    'value'=>$data->$key,
							    'minRating'=>1, //minimal value
								'maxRating'=>5,//max value
								'starCount'=>5, //number of stars
							    'readOnly'=>true,
							));
				     		//echo CHtml::encode($data->$key);
					     
					 		}
					    
					     echo "<br>";
					   
				    }
					?>

				</td>
				<td>
					<p>
						<?php
					$model=new Comment;
					$a='';
					$colArr=$model->attributes;

					$thuoctinh =array("von hieu biet","a","b","c");
					echo "<p>";
				    for ($i = 0; $i <  count($colArr); $i++) {
				    	// echo($thuoctinh[$i]);
				    	$a=$a+$data->id;
					    $key=key($colArr);
					    //die();
					    $val=$colArr[$key];
						next($colArr);	     	
					    if ($key=='average'){
					   	echo $key.": ";
					   	echo CHtml::encode($data->$key);
					   }
				    }
					?>
					<br>
					<?php echo nl2br(CHtml::encode($data->message)); ?>	
					</p>
				</td>
			</tr>
		</table>






	

	
	
	
	<br style="clear: both;"/>
</div>