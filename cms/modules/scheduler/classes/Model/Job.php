<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Jobs
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Job extends ORM {
	
	const STATUS_FAILED		= -1;	// Job has failed
	const STATUS_NEW		= 1;	// New job
	const STATUS_RUNNING	= 2;	// Job is currently running
	const STATUS_COMPLETE	= 3;	// Job is complete
	
	const AGENT_SYSTEM = 0;
	const AGENT_CRON = 1;
	
	const MAX_ATEMTPS = 5;
	
	/**
	 * 
	 * @return array
	 */
	public static function agents()
	{
		return array(
			Model_Job::AGENT_SYSTEM => __('System'),
			Model_Job::AGENT_CRON => __('Crontab')
		);
	}

	/**
	 * 
	 * @return array
	 */
	public static function statuses()
	{
		return array(
			Model_Job::STATUS_NEW => __('New job'),
			Model_Job::STATUS_FAILED => __('Job has failed'),
			Model_Job::STATUS_RUNNING => __('Job is currently running'),
			Model_Job::STATUS_COMPLETE => __('Job is complete'),
		);
	}
	
	protected $_created_column = array(
		'column' => 'created_on',
		'format' => 'Y-m-d H:i:s'
	);
	
	protected $_has_many = array(
		'logs' => array('model' => 'job_log')
	);
	
	public function before_create()
	{
		$this->set_next_run();
		return parent::before_create();
	}

	public function labels()
	{
		return array(
			'name' => __('Job name'),
			'job' => __('Job function'),
			'date_start' => __('Job run start'),
			'date_end' => __('Job run end'),
			'interval' => __('Interval'),
			'crontime' => __('Crontime string')
		);
	}

	public function rules()
	{
		return array(
			'date_start' => array(
				array('date')
			),
			'date_end' => array(
				array('date')
			),
			'job' => array(
				array('not_empty')
			),
			'name' => array(
				array('not_empty')
			),
			'crontime' => array(
				array('Crontab::valid')
			)
		);
	}
	
	public function filters()
	{
		return array(
			'interval' => array(
				array('intval')
			),
			'attempts' => array(
				array('intval')
			),
		);
	}
	
	public function form_columns()
	{
		return array(
			'id' => array(
				'type' => 'input',
				'editable' => FALSE
			),
			'name' => array(
				'type' => 'input'
			),
			'job' => array(
				'type' => 'select',
				'choices' => array($this, 'get_types')
			),
			'date_start' => array(
				'type' => function($object, $field, $attributes) {
					return Form::input($field, $object->date_start(), $attributes);
				}
			),
			'date_end' => array(
				'type' => function($object, $field, $attributes) {
					return Form::input($field, $object->date_end(), $attributes);
				}
			),
		);
	}
	
	public function set_next_run()
	{
		if (empty($this->interval) AND empty($this->crontime))
		{
			return;
		}

		if (!empty($this->crontime))
		{
			$this->date_next_run = date('Y-m-d H:i:s', Crontab::parse($this->crontime));
		}
		else if (!empty($this->interval))
		{
			$this->date_next_run = date('Y-m-d H:i:s', time() + $this->interval);
		}

		return $this;
	}

	public function complete()
	{
		$this->set_next_run();

		return $this->values(array(
			'status' => Model_Job::STATUS_COMPLETE,
			'date_last_run' => date('Y-m-d H:i:s'),
			'attempts' => 0
		))->save();
	}
	
	public function failed()
	{
		$this->set_next_run();
		
		return $this->values(array(
			'status' => Model_Job::STATUS_FAILED,
			'date_last_run' => date('Y-m-d H:i:s'),
			'attempts' => $this->attempts + 1
		))->save();
	}
	
	/**
	 * 
	 * @return string
	 */
	public function status()
	{
		return Arr::get(Model_Job::statuses(), $this->status, 1);
	}
	
	/**
	 * 
	 * @return string
	 */
	public function date_start()
	{
		return empty($this->date_start) ? date('Y-m-d H:i:s') : $this->date_start;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function date_end()
	{
		return empty($this->date_end) ? date('Y-m-d H:i:s', strtotime(' +10 years')) : $this->date_end;
	}

	/**
	 * 
	 * @param integer $status
	 * @return Model_Job
	 * @throws Kohana_Exception
	 */
	public function set_status($status)
	{
		if (!$this->loaded())
		{
			throw new Kohana_Exception('Cannot set status because it is not loaded.');
		}

		$this->status = $status;
		return $this->save();
	}

	public function run_all()
	{
		$jobs = $this
			->where('attempts', '<=', Model_Job::MAX_ATEMTPS)
			->where(DB::expr('NOW()'), 'between', DB::expr('date_start AND date_end'))
			->where('date_next_run', '<', DB::expr('NOW()'))
			->find_all();
		
		foreach ($jobs as $job)
		{
			$job->run();
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @return Model_Job_log
	 */
	public function run()
	{
		return $this->logs->run($this);
	}
	
	public function get_types()
	{
		return Config::get('jobs')->as_array();
	}
}