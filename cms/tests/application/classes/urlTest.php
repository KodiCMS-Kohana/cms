<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');

class URLTest extends Unittest_TestCase {

	public function provider_has_suffix()
	{
		return array(
			array('http://test.com/test.txt', TRUE),
			array('http://test.com/test' . URL_SUFFIX, TRUE),
			array('http://test.com/test#test', FALSE),
			array('http://test.com/test?123', FALSE),
			array('http://test.com/test', FALSE),
			array('http://test.com/test/', FALSE)
		);
	}

	/**
	 * @covers URL::has_suffix
	 * @dataProvider provider_has_suffix
	 */
	public function test_has_suffix($uri, $expected)
	{
		$this->assertEquals(URL::has_suffix($uri), $expected);
	}
	
	public function provider_has_segment()
	{
		return array(
			array('/test.txt', 'test.txt', TRUE),
			array('/test.txt', 'text', FALSE),
			array('/test/test1/test2' . URL_SUFFIX, 'test1', TRUE)
		);
	}

	/**
	 * @covers URL::has_segment
	 * @dataProvider provider_has_segment
	 */
	public function test_has_segment($uri, $segment, $expected)
	{
		$this->assertEquals(URL::has_segment($segment, $uri), $expected);
	}

	public function provider_backend()
	{
		return array(
			array('test', '/' . ADMIN_DIR_NAME . '/test'),
			array('/test', '/' . ADMIN_DIR_NAME . '/test'),
			array(ADMIN_DIR_NAME . '/test', '/' . ADMIN_DIR_NAME . '/test'),
			array('/' . ADMIN_DIR_NAME . '/test', '/' . ADMIN_DIR_NAME . '/test'),
			array(ADMIN_DIR_NAME, '/' . ADMIN_DIR_NAME),
			array(ADMIN_DIR_NAME . 'abc', '/' . ADMIN_DIR_NAME . '/' . ADMIN_DIR_NAME . 'abc'),
		);
	}

	/**
	 * @covers URL::backend
	 * @dataProvider provider_backend
	 */
	public function test_backend($uri, $expected)
	{
		$this->assertSame(URL::backend($uri), $expected);
	}

	public function provider_frontend()
	{
		return array(
			array('test', '/test' . URL_SUFFIX),
			array('test' . URL_SUFFIX, '/test' . URL_SUFFIX),
			array('test.xml', '/test.xml'),
			array('test#abc', '/test' . URL_SUFFIX . '#abc'),
		);
	}

	/**
	 * @covers URL::frontend
	 * @dataProvider provider_frontend
	 */
	public function test_frontend($uri, $expected)
	{
		$this->assertSame(URL::frontend($uri), $expected);
	}

}
