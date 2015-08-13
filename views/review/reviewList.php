
<?php

/** @var CArrayDataProvider $reviews */
$reviews = $model->getReviewDataProvider();
$list=$reviews->rawData;
$reviews->setPagination(false);



//$a='review.themes.review.views.review._view';
$a='review.views.review._view';
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$reviews,
    'itemView'=>$a,
    'emptyText'=>'',
    //'id'=>'#review',
));
// $b='review.themes.classic.views.review._form';
$b='review.views.review._form';


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
		echo "You rated this teacher. You view and edit your rate at ";
		echo "<a href=".'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."#ext-review-".$link.">";
								echo "here.";
								echo "</a>";
		

	}
	else
	{
		$this->renderPartial($b, array(
		    'review'=>$model->reviewInstance
		));
	}

