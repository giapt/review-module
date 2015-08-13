
<?php

/** @var CArrayDataProvider $comments */
$comments = $model->getCommentDataProvider();
$list=$comments->rawData;
$comments->setPagination(false);



//$a='comment.themes.review.views.comment._view';
$a='comment.views.comment._viewSchool';
$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$comments,
    'itemView'=>$a,
    'emptyText'=>'',
    //'id'=>'#comment',
));
// $b='comment.themes.classic.views.comment._form';
$b='comment.views.comment._formSchool';


$count=0;
$commentId='';
$link='';
if ((!Yii::app()->user->isGuest) AND ($list<>NULL)) {
	
	$li=$list[0]->key;//find postID

	$userID=Yii::app()->user->id;
	$models = Comment::model()->findAll(
	 					 array("condition"=>"userId= $userID",'select'=>'id',));
	//find all review post by user

	$list3 = CHtml::listData($models, 'id' ,'userId');
	for ($i = 0; $i < count($list3); $i++) {    	
				    	
						    $key=key($list3);
						    if ($key<>NULL) {
						    	$getID= PostsComments::model()->findAll(
											array("condition"=>"commentId=$key AND postId=$li",'select'=>'commentId, postId',));
								
									$list2 = CHtml::listData($getID, 'commentId' ,'postId');
									$commentId=key($list2);//get lay id review ma nguoi dung da post
									if ($commentId<>NULL) {
										$count=$count+1;
										$link=$commentId;
									}

						    }
						     next($list3);
						 }					
}
if ($count<>0) {
		echo "<br>";
		echo "You rated this school. You view and edit your rate at ";
		echo "<a href=".'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."#ext-comment-".$link.">";
								echo "here.";
								echo "</a>";
		

	}
	else
	{
		$this->renderPartial($b, array(
		    'comment'=>$model->commentInstance
		));
	}

