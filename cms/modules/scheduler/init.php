<?php defined('SYSPATH') or die('No direct access allowed.');

Observer::observe('scheduler_callbacks', function() {
	scheduler::add(function($from, $to) {
		$from = date('Y-m-d', $from);
		$to = date('Y-m-d', $to);

		$pages = Model_Page::find(array('where' => array(
			array(DB::expr('DATE(published_on)'), 'between', array($from, $to))
		)));

		$data = array();
		foreach ($pages as $page)
		{
			$data[] = array(
				'title' => $page->title,
				'start' => strtotime($page->published_on),
				'url' => URL::backend('page/edit/' . $page->id),
				'allDay' => FALSE
			);
		}
		return $data;
	});
});