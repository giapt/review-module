
<br>
<br> 	
<?php if (Yii::app()->user->isGuest) {
?><div class="ext-review-not-loggedin">
	Sorry, you have to login to leave a review.
</div><?php } else { ?>
<div id="ext-review-form-<?php echo $review->isNewRecord ? 'new' : 'edit-'.$review->id; ?>" class="form" >
<div id = "review-khung1">
	<hr>
	<br>
	<h2>Let rate</h2>
<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'ext-review-form',
    'action'=>array('/review/review/create'),
	'enableAjaxValidation'=>false
)); ?>
	<div>
		
	<div id = "view_review_abc">
			<?php /** @var CActiveForm $form */
				echo $form->errorSummary($review); ?>				
				 <?php 
	    	
			    	$model=new Review;
					$colArr=$model->attributes;
				    for ($i = 0; $i < count($colArr); $i++) {
				    	
				    	
					    $key=key($colArr);
					    //$val=$colArr[$key];
					    
				     	if (($key<> 'average') and ($key<>'id') and ($key<>'message') and ($key<>'userId')
				     	 and ($key<>'createDate') ){
				     	// 	echo "<br>".$key.": ".$model->$key."<br>";
				     	 if (isset($review)) {
				     	 	$b=$review->$key;
				     	 }
				     	 else $b=0;
					     $this->widget('CStarRating',array(
					     				'value'=>$b,
								        'name'=>'review['.$key.']',
								        'minRating'=>1, //minimal value
								        'maxRating'=>5,//max value
								        'starCount'=>5, //number of stars
								        ));
					 		$c=$model->getAttributeLabel($key);
					 	// 	if ($c=='Rate1')
					 	// 	{echo "&nbsp;&nbsp;&nbsp;Easiness<br>";}
					 	// 	if ($c=='Rate2')
					 	// 	{echo "&nbsp;&nbsp;&nbsp;Helpfulness<br>";}
					 	// if ($c=='Rate3')
					 	// 	{echo "&nbsp;&nbsp;&nbsp;Clarity<br>";}
					 	// if ($c=='Rate4')
					 	// 	{echo "&nbsp;&nbsp;&nbsp;Knowledge<br>";}
					 	// if ($c=='Rate5')
					 	// 	{echo "&nbsp;&nbsp;&nbsp;Exam difficulty<br>";}
						     
						// else
						// {
							echo "&nbsp;&nbsp;&nbsp;".$model->getAttributeLabel($key)."<br>";
						     
						// }
					    
					     echo "<br>";}

					     next($colArr);
					 }
					 
					
					 ?>
	</div>
	
				<div id = "view_review_form">
					
					<p>Tell some memory about your teacher</p>
					<?php echo $form->textArea($review,'message',array('rows'=>7, 'cols'=>65	)); ?>
					
					<?php echo $form->error($review,'message'); ?>
				</div>
		
</div>
	
<div id = "review-submit">
	   <?php if ($review->isNewRecord) {
	    	echo "<br>";
			echo $form->hiddenField($review, 'type');
			echo $form->hiddenField($review, 'key');
         
			echo CHtml::ajaxSubmitButton('Submit',
                array('/review/review/create'),
		        array(
                    'replace'=>'#ext-review-form-new',
                    'error'=>"function(){
                        $('#review_message').css('border-color', 'red');
                        $('#review_message').css('background-color', '#fcc');
                    }"
		        ),
		        array('id'=>'ext-review-submit' . (isset($ajaxId) ? $ajaxId : ''))
		    );
		} else {
			echo CHtml::ajaxSubmitButton('Update',
				array('/review/review/update', 'id'=>$review->id),
				array(
					'replace'=>'#ext-review-form-edit-'.$review->id,
					'error'=>"function(){
						$('#review_message').css('border-color', 'red');
						$('#review_message').css('background-color', '#fcc');
					}"
		        ),
		        array('id'=>'ext-review-submit' . (isset($ajaxId) ? $ajaxId : ''))
	        );
		}
		// echo "<br>";
		?>
	</div>
<?php $this->endWidget() ?>
</div>
</div><!-- form -->
<?php } ?>