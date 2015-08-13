
<?php

/** @var CArrayDataProvider $reviews */
$reviews = $model->getReviewDataProvider();
$list=$reviews->rawData;
$reviews->setPagination(false);

$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$reviews,
    'itemView'=>'review.views.review._view',
    'emptyText'=>'',
));


$count=0;
$reviewId='';
$link='';
if ((!Yii::app()->user->isGuest) AND ($list<>NULL)) {
	
	$li=$list[0]->key;//find postID

	$userID=Yii::app()->user->id;
	$models = Review::model()->findAll(
	 					 array("condition"=>"userId= $userID",'select'=>'id',));
	//find all review post by user

	$list3 = CHtml::listData($models, 'id' ,'userId');
	for ($i = 0; $i < count($list3); $i++) {    	
				    	
						    $key=key($list3);
						    if ($key<>NULL) {
						    	$getID= PostsReviews::model()->findAll(
											array("condition"=>"reviewId=$key AND postId=$li",'select'=>'reviewId, postId',));
								
									$list2 = CHtml::listData($getID, 'reviewId' ,'postId');
									$reviewId=key($list2);//get lay id review ma nguoi dung da post
									if ($reviewId<>NULL) {
										$count=$count+1;
										$link=$reviewId;
									}

						    }
						     next($list3);
						 }					
}
if ($count<>0) {
		echo "<br>";
		echo "You rated';. You view and edit your rate at ";
		echo "<a href=".'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."#ext-review-".$link.">";
								echo "here.";
								echo "</a>";
		

	}
	else
	{
		$this->renderPartial('review.views.review._form', array(
		    'review'=>$model->reviewInstance
		));
	}

