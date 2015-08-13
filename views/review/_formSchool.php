<br>
<br> 	
<?php if (Yii::app()->user->isGuest) {
?><div class="ext-comment-not-loggedin">
	Sorry, you have to login to leave a review.
</div><?php } else { ?>
<div id="ext-comment-form-<?php echo $comment->isNewRecord ? 'new' : 'edit-'.$comment->id; ?>" class="form" >
<div id = "comment-khung1">
	<hr>
	<br>
	<h2>Hãy đánh giá trường học của bạn</h2>
<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'ext-comment-form',
    'action'=>array('/comment/comment/create'),
	'enableAjaxValidation'=>false
)); ?>
	<div>
		
	<div id = "view_comment_abc">
			<?php /** @var CActiveForm $form */
				echo $form->errorSummary($comment); ?>				
				 <?php 
	    	
			    	$model=new Comment;
					$colArr=$model->attributes;
				    for ($i = 0; $i < count($colArr); $i++) {
				    	
				    	
					    $key=key($colArr);
					    //$val=$colArr[$key];
					    
				     	if (($key<> 'average') and ($key<>'id') and ($key<>'message') and ($key<>'userId')
				     	 and ($key<>'createDate') ){
				     		
					     $this->widget('CStarRating',array(
								        'name'=>'Comment['.$key.']',
								        'minRating'=>1, //minimal value
								        'maxRating'=>5,//max value
								        'starCount'=>5, //number of stars
								        ));
					 		$c=$model->getAttributeLabel($key);
					 		if ($c=='Rate1')
					 		{echo "&nbsp;&nbsp;&nbsp;Reputation<br>";}
					 		if ($c=='Rate2')
					 		{echo "&nbsp;&nbsp;&nbsp;Location<br>";}
					 	if ($c=='Rate3')
					 		{echo "&nbsp;&nbsp;&nbsp;Opportunities<br>";}
					 	if ($c=='Rate4')
					 		{echo "&nbsp;&nbsp;&nbsp;Library<br>";}
					 	if ($c=='Rate5')
					 		{echo "&nbsp;&nbsp;&nbsp;Internet<br>";}
						     echo "<br>";
						 }
					    
					     
					     next($colArr);
					 }
					 
					
					 ?>
	</div>
	
				<div id = "view_comment_form">
					
					<p>Kể lại một câu nói, kỷ niệm của thầy cô</p>
					<?php echo $form->textArea($comment,'message',array('rows'=>7, 'cols'=>65	)); ?>
					
					<?php echo $form->error($comment,'message'); ?>
				</div>
		
</div>
	
<div id = "comment-submit">
	   <?php if ($comment->isNewRecord) {
	    	echo "<br>";
			echo $form->hiddenField($comment, 'type');
			echo $form->hiddenField($comment, 'key');
         
			echo CHtml::ajaxSubmitButton('Submit',
                array('/comment/comment/create'),
		        array(
                    'replace'=>'#ext-comment-form-new',
                    'error'=>"function(){
                        $('#Comment_message').css('border-color', 'red');
                        $('#Comment_message').css('background-color', '#fcc');
                    }"
		        ),
		        array('id'=>'ext-comment-submit' . (isset($ajaxId) ? $ajaxId : ''))
		    );
		} else {
			echo CHtml::ajaxSubmitButton('Update',
				array('/comment/comment/update', 'id'=>$comment->id),
				array(
					'replace'=>'#ext-comment-form-edit-'.$comment->id,
					'error'=>"function(){
						$('#Comment_message').css('border-color', 'red');
						$('#Comment_message').css('background-color', '#fcc');
					}"
		        ),
		        array('id'=>'ext-comment-submit' . (isset($ajaxId) ? $ajaxId : ''))
	        );
		}
		// echo "<br>";
		?>
	</div>
<?php $this->endWidget() ?>
</div>
</div><!-- form -->
<?php } ?>