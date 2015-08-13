<?php

/**
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package yiiext.modules.review
 */
class ReviewEvent extends CEvent
{
	/**
	 * @var review the review related to this event
	 */
	public $review = null;

	/**
	 * @var CActiveRecord the reviewed object if available
	 */
	public $reviewedModel = null;
}
