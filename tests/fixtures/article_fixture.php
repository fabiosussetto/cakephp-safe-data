<?php
class ArticleFixture extends CakeTestFixture {

	public $name = 'Article';

	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false),
		'content' => array('type' => 'text', 'null' => false),
		'html_allowed' => array('type' => 'text', 'null' => false)
	);


	public $records = array();

}
