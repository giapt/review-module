Review module
--------------
Review module, extended from CeBe's [comment-module](http://www.yiiframework.com/extension/comment-module/) 

--------------

makes every entity of your application reviewable.
Features:

* Review with rating star and message
* Multiple rating by add column to `reviews` table (example rate6,rate7... with int format) and add name column to function rules() in review model, it will auto update in view and form.
* Create, Update, Delete reviews with ajax
* define multiple models that can be reviewed
* Events raised on new, update, delete
* Update Model target value (you should fix function afterSave(), beforeDelete() in Review model to update value in model review)
*One user can only have onetime to review every object. When you haved review, it will show notification and show your review to edit or delete 


* more coming soon...


Requirements
------------

* Tested with Yii 1.1.16, should work in earlier versions


Download
--------

There are two ways to get this extension working:

1. Clone repo:
   * Go to your application baseDir (`protected` in default yii webapp).
   * `git clone https://github.com/giapt/review-module.git modules/review-module`

2. [Download](https://github.com/giapt/review-module/tags) latest release and put all the files into
   `modules/review-module` under your application baseDir (`protected` in default yii webapp).
   

Quickstart
----------

Add module to your application config (optional config values are reviewed):

~~~php
<?php
    // ...
    'review'=>array(
            'class'=>'application.modules.review-module.ReviewModule',
            'reviewableModels'=>array(
                // define reviewable Models here (key is an alias that must be lower case, value is the model class name)
                'post'=>'Post'
            ),
            // set this to the class name of the model that represents your users
            'userModelClass'=>'User',
            // set this to the username attribute of User model class
            'userNameAttribute'=>'username',
            // set this to the email attribute of User model class
            'userEmailAttribute'=>'email',
            
            'updateModelAuto'=>'off',
            // you can set controller filters that will be added to the review controller {@see CController::filters()}
//          'controllerFilters'=>array(),
            // you can set accessRules that will be added to the review controller {@see CController::accessRules()}
//          'controllerAccessRules'=>array(),
            // you can extend review class and use your extended one, set path alias here
//          'reviewModelClass'=>'review.models.Review',
        ),
    // ...
~~~

Create database tables:
You can use the database migration provieded by this extension or create a table (example for mysql) or import table in data folder:

~~~sql
    DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8_unicode_ci,
  `rate1` int(11) NOT NULL,
  `rate2` int(11) NOT NULL,
  `rate3` int(11) NOT NULL,
  `rate4` int(11) NOT NULL,
  `rate5` int(11) NOT NULL,
  `userId` int(11) unsigned DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comments_userId` (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
~~~
You might also want to add a foreign key for `userId` column that references you user tables pk.

Create a database table for every reviewable Model relation:

~~~sql
    DROP TABLE IF EXISTS `posts_reviews_nm`;
    CREATE TABLE IF NOT EXISTS `posts_reviews_nm` (
      `postId` int(11) unsigned NOT NULL,
      `reviewId` int(11) unsigned NOT NULL,
      PRIMARY KEY (`postId`,`reviewId`),
      KEY `fk_posts_comments_comments` (`reviewId`),
      KEY `fk_posts_comments_posts` (`postId`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
~~~
You might want to add foreign keys here too.

Add reviewable behavior to all Models you want to be reviewed.

~~~php
<?php
    // ...
    public function behaviors() {
        return array(
            'reviewable' => array(
                'class' => 'ext.review-module.behaviors.reviewableBehavior',
                // name of the table created in last step
                'mapTable' => 'posts_reviews_nm',
                // name of column to related model id in mapTable
                'mapRelatedColumn' => 'postId'
            ),
       );
    }
~~~

Finally add reviews to your view template of the reviewable model:

~~~php

<?php $this->renderPartial('review.views.review.reviewList', array(
  'model'=>$model
)); ?>
~~~


