<?php

/**
 * Add this behavior to AR Models that are reviewable
 * You have to create mapping Table to build the relation in
 * your database. A migration for creating such a table could look like this:
 * <pre>
 * class m111212_030738_add_review_task_relation extends CDbMigration
 * {
 *     public function up()
 *     {
 *         $this->createTable('tasks_reviews_nm', array(
 *             'taskId' => 'bigint(20) unsigned NOT NULL',
 *              'reviewId' => 'int',
 *              'PRIMARY KEY(taskId, reviewId)',
 *              'KEY `fk_tasks_reviews_reviews` (`reviewId`)',
 *              'KEY `fk_tasks_reviews_tasks` (`taskId`)',
 *         ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
 *
 *         $this->addForeignKey('fk_tasks_reviews_reviews', 'tasks_reviews_nm', 'reviewId', 'reviews', 'id', 'CASCADE', 'CASCADE');
 *         $this->addForeignKey('fk_tasks_reviews_tasks', 'tasks_reviews_nm', 'taskId', 'tasks', 'id', 'CASCADE', 'CASCADE');
 *     }
 *
 *     public function down()
 *     {
 *         $this->dropTable('tasks_reviews_nm');
 *     }
 * }
 * </pre>
 * In behavio config you have to set {@see $mapTable} to the name of the table
 * and {@see $mapReviewColumn} and {@see $mapRelatedColumn} to the column names you chose.
 * <pre>
 * public function behaviors() {
 *     return array(
 *         'reviewable' => array(
 *              'class' => 'ext.review-module.behaviors.reviewableBehavior',
 *              'mapTable' => 'tasks_reviews_nm',
 *              'mapRelatedColumn' => 'taskId'
 *              'mapReviewColumn' => 'reviewId'
 *          ),
 *     );
 * }
 * </pre>
 *
 * @property ReviewModule $module
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package yiiext.modules.review
 */
class ReviewableBehavior extends CActiveRecordBehavior
{
	/**
	 * @var string name of the table defining the relation with review and model
	 */
	public $mapTable = null;
	/**
	 * @var string name of the table column holding reviewId in mapTable
	 */
	public $mapReviewColumn = 'reviewId';
	/**
	 * @var string name of the table column holding related Objects Id in mapTable
	 */
	public $mapRelatedColumn = null;

	public function attach($owner)
	{
		parent::attach($owner);
		// make sure review module is loaded so views can be rendered properly
		Yii::app()->getModule('review');
	}

	/**
	 * @return ReviewModule
	 */
	public function getModule()
	{
		return Yii::app()->getModule('review');
	}

	/**
	 * returns a new review instance that is related to the model this behavior is attached to
	 *
	 * @return review
	 * @throws CException
	 */
	public function getReviewInstance()
	{
		$review = Yii::createComponent($this->module->reviewModelClass);
		$types = array_flip($this->module->reviewableModels);
		if (!isset($types[$c=get_class($this->owner)])) {
			throw new CException('No scope defined in ReviewModule for reviewable Model ' . $c);
		}
		$review->setType($types[$c]);
		$review->setKey($this->owner->primaryKey);
		return $review;
	}

	/**
	 * get all related reviews for the model this behavior is attached to
	 *
	 * @return review[]
	 * @throws CException
	 */
	public function getReviews()
	{
		$reviews = Yii::createComponent($this->module->reviewModelClass)
					     ->findAll($this->getReviewCriteria());
		// get model type
		$type = get_class($this->owner);
		foreach($this->module->reviewableModels as $scope => $model) {
			if ($type == $model) {
				$type = $scope;
				break;
			}
		}
		foreach($reviews as $review) {
			/** @var review $review */
			$review->setType($type);
			$review->setKey($this->owner->primaryKey);
		}
		return $reviews;
	}

	/**
	 * count all related reviews for the model this behavior is attached to
	 *
	 * @return int
	 * @throws CException
	 */
	public function getReviewCount()
	{
		return Yii::createComponent($this->module->reviewModelClass)
					->count($this->getReviewCriteria());
	}

	protected function getReviewCriteria()
	{
		if (is_null($this->mapTable) || is_null($this->mapRelatedColumn)) {
			throw new CException('mapTable and mapRelatedColumn must not be null!');
		}
		$type=get_class($this->owner);
		$type=strtolower($type);

		// @todo: add support for composite pks
		return new CDbCriteria(array(
			'join' => "JOIN " . $this->mapTable . " cm ON t.id = cm." . $this->mapReviewColumn,
		    'condition' => "cm." . $this->mapRelatedColumn . "=:pk AND cm.type=:type",
			'params' => array(':pk'=>$this->owner->getPrimaryKey(), 'type'=> $type),
		));
	}

	/**
	 * @todo this should be moved to a controller or widget
	 *
	 * @return CArrayDataProvider
	 */
	public function getReviewDataProvider()
	{
		return new CArrayDataProvider($this->getReviews());
	}
}
