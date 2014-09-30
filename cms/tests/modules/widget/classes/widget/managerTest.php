<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

class Widget_ManagerTest extends Unittest_TestCase {

	public function provider_map()
    {
        return array(
            array('widgets'),
			array('HAHAHAHAH')
        ); 
    }
	
	/**
	 * @test
	 * @dataProvider provider_map
	 */
	public function test_map($type)
	{
		$map = Widget_Manager::map($type);
		$this->assertTrue(is_array($map));
	}

	/**
	 * @covers Widget_Manager::factory
	 */
	public function test_factory()
	{
		$widget = Widget_Manager::factory('html');
		$this->assertTrue($widget instanceof Model_Widget_Decorator);
	}
	
	/**
	 * @covers Widget_Manager::factory
	 */
	public function test_factoryException()
	{
		$this->setExpectedException('Kohana_Exception');
		Widget_Manager::factory('HAHAHAHAH');
	}

	public function provider_get_widgets()
    {
        return array(
            array(array('hybrid')),
			array(NULL),
			array(array()),
			array(array('HAHAHAHAH'))
        ); 
    }
	
	/**
	 * @covers Widget_Manager::get_widgets
	 * @dataProvider provider_get_widgets
	 */
	public function test_get_widgets($types)
	{
		$widgets = Widget_Manager::get_widgets($types);
		
		$this->assertTrue(is_array($widgets));
		
		foreach ($widgets as $widget)
		{
			$this->assertTrue($widget instanceof Model_Widget_Decorator);
		}
	}

	/**
	 * @covers Widget_Manager::get_all_widgets
	 */
	public function test_get_all_widgets()
	{
		$widgets = Widget_Manager::get_all_widgets();
		$this->assertTrue(is_array($widgets));
	}

	public function provider_get_widgets_by_page()
    {
        return array(
            array(1),
			array(NULL),
			array(''),
			array(array())
        ); 
    }
	
	/**
	 * @covers Widget_Manager::get_widgets_by_page
	 * @dataProvider provider_get_widgets_by_page
	 */
	public function test_get_widgets_by_page($page_id)
	{
		$widgets = Widget_Manager::get_widgets_by_page($page_id);
		
		$this->assertTrue(is_array($widgets));
		
		foreach ($widgets as $widget)
		{
			$this->assertTrue($widget instanceof Model_Widget_Decorator);
		}
	}

	/**
	 * @covers Widget_Manager::copy
	 */
	public function test_copy()
	{
		$status = Widget_Manager::copy(1, 2);
		$this->assertTrue($status);
	}
	
	public function provider_create()
    {
		$widget = Widget_Manager::factory('html');
		$widget->name = 'test';
		$widget->description = 'test';

        return array(
            array($widget, $widget)
        ); 
    }

	/**
	 * @covers Widget_Manager::create
	 * @dataProvider provider_create
	 */
	public function test_create($widget, $widget1)
	{		
		$id = Widget_Manager::create($widget);
		$this->assertTrue(Valid::numeric($id) > 0);
		
		$this->assertTrue($widget instanceof Model_Widget_Decorator);
		$this->assertTrue($widget->name == $widget1->name);
		$this->assertTrue($widget->description == $widget1->description);
	}
	
	public function test_createValidation()
	{
		$this->setExpectedException('ORM_Validation_Exception');
		$id = Widget_Manager::create(Widget_Manager::factory('html'));
	}

	/**
	 * @covers Widget_Manager::update
	 * @todo   Implement testUpdate().
	 */
	public function test_update()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}
	
	public function provider_update()
    {
		$widget = Widget_Manager::factory('html');
		$widget->name = 'test';
		$widget->description = 'test';

        return array(
            array($widget, $widget)
        ); 
    }
	
	public function test_updateException()
	{
		$this->setExpectedException('Kohana_Exception');
		Widget_Manager::update(Widget_Manager::factory('html'));
	}

	/**
	 * @covers Widget_Manager::remove
	 * @todo   Implement testRemove().
	 */
	public function testRemove()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Widget_Manager::load
	 * @todo   Implement testLoad().
	 */
	public function testLoad()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Widget_Manager::set_location
	 * @todo   Implement testSet_location().
	 */
	public function testSet_location()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Widget_Manager::update_location_by_page
	 * @todo   Implement testUpdate_location_by_page().
	 */
	public function testUpdate_location_by_page()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Widget_Manager::install
	 * @todo   Implement testInstall().
	 */
	public function testInstall()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Widget_Manager::get_system_blocks
	 * @todo   Implement testGet_system_blocks().
	 */
	public function testGet_system_blocks()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Widget_Manager::get_blocks_by_layout
	 * @todo   Implement testGet_blocks_by_layout().
	 */
	public function testGet_blocks_by_layout()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Widget_Manager::exists_by_type
	 * @todo   Implement testExists_by_type().
	 */
	public function testExists_by_type()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Widget_Manager::get_related
	 * @todo   Implement testGet_related().
	 */
	public function testGet_related()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Widget_Manager::get_params
	 * @todo   Implement testGet_params().
	 */
	public function testGet_params()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

}
