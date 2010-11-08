<?php
/**
 * CakePHP Sanitizable Behavior
 *
 * Copyright 2010, Fabio Sussetto
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2010, Fabio Sussetto
 * @link      
 * @package   plugins.safe_data
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * A behavior using the Sanitize core library, 
 * in order to automatically sanitize user submitted data before they get stored in the db
 * 
 */
 
class SanitizableBehavior extends ModelBehavior {
		
		public $settings = array();
		
/**
 * defaults settings
 *
 * mode - encode: convert html to entities.
 * 			-	strip: strip html tags from input.
 * 			- invalidate: validation fails if any field contains html data
 * allowedTags - array of tags not to be processed. Not yet implemented.
 * allowedFields - array of fields NOT to to be processed.
 * fieldOptions - for each field (key) you can specify an array of options which override the default behavior settings
 * 								for the specific field.
 * @var array
 * @access protected
 */
		protected $_defaultSettings = array(
			'mode' => 'encode',
			'allowedTags'	=> array(),
			'allowedFields' => array(),
			'fieldOptions' => array()
		);
		
		public function setup(Model $model, $config = array()) {
   		$this->settings[$model->alias] = Set::merge($this->_defaultSettings, $config);
		}
	
		public function beforeSave(Model $model) {
			if (!class_exists('Sanitize')) {
				App::import('Core', 'Sanitize');
			}
			extract($this->settings[$model->alias]);
			
			$cleanOptions = array(
				'remove_html' => ($mode == 'strip')
			);
			
			foreach ($model->data[$model->alias] as $field => &$value) {
				if (!in_array($field, $allowedFields)) {
					$value = Sanitize::clean($value, $cleanOptions);
				}
			}
		}
		
		
}