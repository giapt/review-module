<?php


class ReviewModule extends CWebModule
{
	/**
	 * @var array associative array of 'scopename' to reviewable models 'modelclass'
	 *
	 * 'scopename' must be lower case and is an alias for the model
	 * class name that will be send with the create review http request.
	 *
	 * 'modelclass' is a class name of the reviewable AR
	 * this AR class must have the {@see reviewableBehavior} attached to it
	 */
	public $reviewableModels = array();

	/**
	 * @var string name of the user model class to use for reviews
	 */
	public $userModelClass = 'User';
	/**
	 * @var string attribute which holds the name of the user in {@see $userModelClass}
	 */

	public $updateModelAuto = 'off';

	public $userNameAttribute = 'name';
	/**
	 * @var string attribute which holds the email of the user in {@see $userModelClass}
	 */
	public $userEmailAttribute = 'email';
	/**
	 * @var array you can set filters that will be added to the review controller {@see CController::filters()}
	 */
	public $controllerFilters = array();
	/**
	 * @var array you can set accessRules that will be added to the review controller {@see CController::accessRules()}
	 */
	public $controllerAccessRules = array();
	/**
	 * @var string allows you to extend review class and use your extended one, set path alias here
	 */
	public $reviewModelClass = 'review.models.Review';

	public $theme = 'giap';
	// private $_assetsUrl;

	// public function getAssetsUrl()
	// {
	//     if ($this->_assetsUrl === null)
	//         $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
	//             Yii::getPathOfAlias('review.assets') );
	//     return $this->_assetsUrl;
	// }

    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport(array(
	        'review.models.*',
	        'review.assets.*',
	        'review.behaviors.*',
        ));

        //$this->layoutPath  = Yii::app()->basePath.'/extensions/review-module/themes/';
        //Yii::app()->themeManager->basePath = Yii::app()->basePath.'/../themes/';
        /*echo "<pre>";
        var_dump(Yii::app()->themeManager->basePath);
        echo "</pre>";
        die();*/
        //Yii::app()->theme = 'giap';
        //Yii::app()->theme=$this->theme;
    }

    public function beforeControllerAction($controller, $action)
    {
	    // @todo: what to do if user is not loggend in and want to review?
        if(parent::beforeControllerAction($controller, $action))
        {
            // this method is called before any module controller action is performed
            // you may place customized code here
            //$controller->layout = 'classic';
            return true;
        }
        else
            return false;
    }

	/**
	 * This event is raised after a new review has been added
	 *
	 * @param $review
	 * @param $model
	 */
	public function onNewReview($review, $model)
	{
		$event = new ReviewEvent();
		$event->review = $review;
		$event->reviewedModel = $model;
		$this->raiseEvent('onNewReview', $event);
	}

	/**
	 * This event is raised after a review has been updated
	 *
	 * @param $review
	 * @param $model currently not available see {@link https://github.com/yiiext/review-module/issues/10}
	 */
	public function onUpdateReview($review/*, $model*/)
	{
		$event = new ReviewEvent();
		$event->review = $review;
		//$event->reviewedModel = $model;
		$this->raiseEvent('onUpdateReview', $event);
	}

	/**
	 * This event is raised after a review got deleted
	 *
	 * @param $review
	 * @param $model currently not available see {@link https://github.com/yiiext/review-module/issues/10}
	 */
	public function onDeleteReview($review/*, $model*/)
	{
		$event = new ReviewEvent();
		$event->review = $review;
		//$event->reviewedModel = $model;
		$this->raiseEvent('onDeleteReview', $event);
	}
}