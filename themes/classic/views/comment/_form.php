<?php if (Yii::app()->user->isGuest) {
?><div class="ext-comment-not-loggedin">
	Sorry, you have to login to leave a comment.
</div><?php } else { ?>
<div id="ext-comment-form-<?php echo $comment->isNewRecord ? 'new' : 'edit-'.$comment->id; ?>" class="form" >

<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'ext-comment-form',
    'action'=>array('/comment/comment/create'),
	'enableAjaxValidation'=>false
)); ?>

	<?php /** @var CActiveForm $form */
	echo $form->errorSummary($comment); ?>

	<div class="row">
		<?php echo $form->labelEx($comment,'message'); ?>
		<?php echo $form->textArea($comment,'message',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($comment,'message'); ?>
	</div>

	

<div class="row buttons">


	    <?php if ($comment->isNewRecord) {
	    	

	    	$model=new Comment;
	
	$colArr=$model->attributes;


    for ($i = 0; $i < count($colArr); $i++) {
    	
    	
	    $key=key($colArr);

	    $val=$colArr[$key];
	    
     	if (($key<> 'average') and ($key<>'id') and ($key<>'message') and ($key<>'userId')
     	 and ($key<>'createDate') ){
     		echo $key."<br>";
	     $this->widget('CStarRating',array(
				        'name'=>'Comment['.$key.']',
				        'minRating'=>1, //minimal value
				        'maxRating'=>5,//max value
				        'starCount'=>5, //number of stars
				        ));
	 		}
	     echo "<br>";
	     
	     next($colArr);

	 }
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
		echo "<br>";
		?>

	</div>

<?php $this->endWidget() ?>

</div><!-- form -->
<?php } ?>