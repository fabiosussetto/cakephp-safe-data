<?php

App::import('Core', 'Model');

class Article extends CakeTestModel {
	
	public $actsAs = array(
		'SafeData.Sanitizable' => array(
			'allowedFields' => array('html_allowed')
		)
	);
	
}

class SanitizableTestCase extends CakeTestCase { 

	public $fixtures = array('plugin.safe_data.article'); 

	public function startTest() {
		$this->Article = ClassRegistry::init('Article');
	}
	
	public function testEncode() {
		$data = array(
			'Article' => array(
				'title' => '<strong>Bold</strong> title',
				'content' => 'This contains <script>some bad js and</script> some <p>html</p> content',
				'html_allowed' =>  '<p>This text is <strong>bold</strong> and contained within a paragraph</p>'
			)
		);
		$this->Article->save($data);
		$saved = $this->Article->read();
		$this->assertEqual($saved['Article']['title'], htmlentities($data['Article']['title']));
		$this->assertEqual($saved['Article']['content'], htmlentities($data['Article']['content']));
		$this->assertEqual($saved['Article']['html_allowed'], $data['Article']['html_allowed']);
	}
	
	public function testStrip() {
		$this->Article->Behaviors->attach('SafeData.Sanitizable', array(
			'mode' => 'strip'
		));
		
		$data = array(
			'Article' => array(
				'title' => '<strong>Bold</strong> title',
				'content' => 'This contains <script>some bad js and</script> some <p>html</p> content',
				'html_allowed' =>  '<p>This text is <strong>bold</strong> and contained within a paragraph</p>'
			)
		);
		$this->Article->save($data);
		$saved = $this->Article->read();
		$this->assertEqual($saved['Article']['title'], 'Bold title');
		$this->assertEqual($saved['Article']['content'], 'This contains some bad js and some html content');
		$this->assertEqual($saved['Article']['html_allowed'], $data['Article']['html_allowed']);
	}
	
	/*public function testInvalidate() {
		$this->Article->Behaviors->attach('SafeData.Sanitizable', array(
			'mode' => 'strip'
		));
		
		$data = array(
			'Article' => array(
				'title' => '<strong>Bold</strong> title',
				'content' => 'This contains <script>some bad js and</script> some <p>html</p> content',
				'html_allowed' =>  '<p>This text is <strong>bold</strong> and contained within a paragraph</p>'
			)
		);
		$result = $this->Article->save($data);
		$this->assertFalse($result);
	}*/

	public function endTest() {
		unset($this->Article);
	}

	
}
