<?php

/**
 *
 * @property reviewModule $module
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package yiiext.modules.review
 */
class ReviewController extends CController
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return $this->module->controllerFilters;
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return $this->module->controllerAccessRules;
	}

	/**
	 * Creates a new review.
	 *
	 * On Ajax request:
	 *   on successfull creation review/_view is rendered
	 *   on error review/_form is rendered
	 * On POST request:
	 *   If creation is successful, the browser will be redirected to the
	 *   url specified by POST value 'returnUrl'.
	 */
	public function actionCreate()
	{
		/** @var review $review */
		$review = Yii::createComponent($this->module->reviewModelClass);

		// Unreview the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST[$cClass=get_class($review)]))
		{
			$review->attributes = $_POST[$cClass];
			$review->type = $_POST[$cClass]['type'];
			$review->key  = $_POST[$cClass]['key'];

			// determine current users id
			if (Yii::app()->user->isGuest) {
				$review->userId = null;
			} else {
				$review->userId = Yii::app()->user->id;
			}

			if(Yii::app()->request->isAjaxRequest) {
				$output = '';
				if($review->save())
				{
					// refresh model to replace CDbExpression for timestamp attribute
					$review->refresh();

					// render new review
					$output .= $this->renderPartial('_view',array(
						'data'=>$review,
					), true);

					// create new review model for empty form
					$review = Yii::createComponent($this->module->reviewModelClass);
					$review->type = $_POST[$cClass]['type'];
					$review->key  = $_POST[$cClass]['key'];
				}
				// render review form
				$output .= $this->renderPartial('_form',array(
					'review'=>$review,
					'ajaxId'=>time(),
				), true);
				// render javascript functions
				Yii::app()->clientScript->renderBodyEnd($output);
				echo $output;
				Yii::app()->end();
			} else {
				if($review->save()) {
					$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view','id'=>$review->id));
				} else {
					die();
				print_r($model->getMessages());
				}
			}
		}

		$this->render('create',array(
			'model'=>$review,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$i=1;
		
		$review=$this->loadModel($id);
		
		if(isset($_POST[$cClass=get_class($review)]))
		{
			$review->attributes = $_POST[$cClass];
			
			if ($review->save())
			{
				
				if(Yii::app()->request->isAjaxRequest) {
					// refresh model to replace CDbExpression for timestamp attribute
					$review->refresh();

					// render updated review
					$this->renderPartial('_view',array(
						'data'=>$review,
					));
					
					$i=$i-1;
					//Yii::app()->end();
				} else {
					
					//Yii::app()->end();
					$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view','id'=>$review->id));
				}
			}
		}
		
		
		if (Yii::app()->request->isAjaxRequest)
		{	
			if ($i<>0) {
				$output = $this->renderPartial('_form',array(
				'review'=>$review,
				'ajaxId'=>time(),
			), true);
			}
			
			// echo "<pre>";
			// var_dump($output);
			// echo "</pre>";
			//die();
			// render javascript functions
			Yii::app()->clientScript->renderBodyEnd($output);
			echo $output;
			Yii::app()->end();
		}
		else
		{
			
			$this->render('update',array(
				'model'=>$review,
			));
		}
	
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		// we only allow deletion via POST request
		if(Yii::app()->request->isPostRequest)
		{
			$review = $this->loadModel($id);
			if (!Yii::app()->user->isGuest && (Yii::app()->user->id == $review->userId))
			{
				$review->delete();

				// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
				if (!Yii::app()->request->isAjaxRequest) {
					$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
				}
			}
			else {
				throw new CHttpException(403,'Only review owner can delete his review.');
			}
		}
		else {
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
	}

	/**
	 * Manages all models.
	 */
	/*public function actionAdmin()
	{
		$model=Yii::createComponent($this->module->reviewModelClass, 'search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['review']))
			$model->attributes=$_GET['review'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}*/

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 * @return review
	 */
	public function loadModel($id)
	{
		$model = Yii::createComponent($this->module->reviewModelClass)->findByPk((int) $id);
		if ($model === null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='review-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
