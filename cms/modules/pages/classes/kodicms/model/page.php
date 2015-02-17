<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Pages
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Model_Page extends ORM
{
	const STATUS_DRAFT				= 1;
	const STATUS_PUBLISHED			= 100;
	const STATUS_HIDDEN				= 101;
	const STATUS_PASSWORD_PROTECTED	= 200;

	const LOGIN_NOT_REQUIRED	= 0;
	const LOGIN_REQUIRED		= 1;
	const LOGIN_INHERIT			= 2;

	/**
	 * Список правил авторищации
	 * @return array
	 */
	public static function logins()
	{
		return array(
			static::LOGIN_NOT_REQUIRED		=> __('Not required'),
			static::LOGIN_REQUIRED			=> __('Required'),
			static::LOGIN_INHERIT			=> __('inherit')
		);
	}
	
	/**
	 * Список правил для meta robots
	 * @return array
	 */
	public static function robots()
	{
		return array(
			'INDEX, FOLLOW'		=> 'INDEX, FOLLOW',
			'INDEX, NOFOLLOW'	=> 'INDEX, NOFOLLOW',
			'NOINDEX, FOLLOW'	=> 'NOINDEX, FOLLOW',
			'NOINDEX, NOFOLLOW' => 'NOINDEX, NOFOLLOW'
		);
	}

	/**
	 * Список статусов
	 * @return array
	 */
	public static function statuses()
	{
		return array(
			static::STATUS_DRAFT				=> __('Draft'),
			static::STATUS_PASSWORD_PROTECTED	=> __('Password protected'),
			static::STATUS_PUBLISHED			=> __('Published'),
			static::STATUS_HIDDEN				=> __('Hidden')
		);
	}
	
	protected $_created_column = array(
		'format' => 'Y-m-d H:i:s',
		'column' => 'created_on'
	);
	
	protected $_updated_column = array(
		'format' => 'Y-m-d H:i:s',
		'column' => 'updated_on'
	);
	
	protected $_belongs_to = array(
		'author' => array(
			'model'			=> 'user',
			'foreign_key'	=> 'created_by_id'
		),
		'updator' => array(
			'model'			=> 'user',
			'foreign_key'	=> 'updated_by_id'
		),
		'parent' => array(
			'model'			=> 'page',
			'foreign_key'	=> 'parent_id'
		)
	);

	protected $_has_many = array (
		'roles' => array(
			'model' => 'role', 
			'through' => 'page_roles'
		)
	);

	/**
	 *
	 * @var boolean 
	 */
	public $is_expanded = FALSE;
	
	/**
	 *
	 * @var boolean 
	 */
	public $has_children = FALSE;
	
	/**
	 *
	 * @var array 
	 */
	public $children_rows = NULL;
	
	/**
	 * 
	 * @return array
	 */
	public function labels()
	{
		return array(
			'title'				=> __('Page title'),
			'slug'				=> __('Slug'),
			'breadcrumb'		=> __('Breadcrumb'),
			'meta_title'		=> __('Meta title'),
			'meta_keywords'		=> __('Meta keywords'),
			'meta_description'	=> __('Meta description'),
			'robots'			=> __('Robots'),
			'parent_id'			=> __('Parent page'),
			'layout_file'		=> __('Layout'),
			'behavior_id'		=> __('Page type'),
			'status_id'			=> __('Page status'),
			'password'			=> __('Page password'),
			'published_on'		=> __('Published date'),
			'needs_login'		=> __('Needs login'),
			'page_permissions'	=> __('Page permissions'),
			'created_by_id'		=> __('Author'),
			'use_redirect'		=> __('Use redirect'),
			'redirect_url'		=> __('Redirect URL')
		);
	}

	/**
	 * 
	 * @return array
	 */
	public function rules()
	{
		$rules = array(
			'title' => array(
				array('not_empty'),
				array('max_length', array(':value', 255))
			),
			'slug' => array(
				array('max_length', array(':value', 100))
			),
			'status_id' => array(
				array('array_key_exists', array(':value', self::statuses()))
			),
			'needs_login' => array(
				array('array_key_exists', array(':value', self::logins()))
			),
		);

		if ($this->id > 1)
		{
			$rules['slug'][] = array('not_empty');
		}

		return $rules;
	}

	/**
	 * 
	 * @return array
	 */
	public function filters()
	{
		return array(
			'slug' => array(
				array(array($this, 'clean_slug')),
				array('strtolower')
			),
			'parent_id' => array(
				array('intval')
			),
			'status_id' => array(
				array('intval')
			),
			'created_by_id' => array(
				array('intval')
			),
			'updated_by_id' => array(
				array('intval')
			),
			'position' => array(
				array('intval')
			),
			'needs_login' => array(
				array('intval')
			),
			'title' => array(
				array('trim'),
				array('strip_tags')
			),
			'meta_title' => array(
				array('trim'),
				array('strip_tags')
			),
			'breadcrumb' => array(
				array('trim'),
				array('strip_tags')
			),
			'meta_keywords' => array(
				array('trim'),
				array('strip_tags')
			),
			'meta_description' => array(
				array('trim'),
				array('strip_tags')
			),
			'use_redirect' => array(
				array('intval')
			),
			'redirect_url' => array(
				array('trim')
			),
		);		
	}
	
	/**
	 * 
	 * @return array
	 */
	public function form_columns()
	{
		return array(
			'id' => array(
				'type' => 'input',
				'editable' => FALSE,
				'length' => 10
			),
			'title' => array(
				'type' => 'input',
				'length' => 100
			),
			'meta_description' => array(
				'type' => 'textarea'
			),
			'meta_title' => array(
				'type' => 'input'
			),
			'meta_title' => array(
				'type' => 'input'
			),
			'robots' => array(
				'type' => 'select',
				'choices' => 'Model_Page::robots'
			),
			'parent_id' => array(
				'type' => 'select',
				'choices' => array($this, 'get_sitemap')
			),
			'status_id' => array(
				'type' => 'select',
				'choices' => 'Model_Page::statuses'
			),
			'layout_file' => array(
				'type' => 'select',
				'choices' => array($this, 'get_layouts_list')
			),
			'behavior_id' => array(
				'type' => 'select',
				'choices' => 'Behavior::select_choices'
			),
			'needs_login' => array(
				'type' => 'select',
				'choices' => 'Model_Page::logins'
			),
			'created_by_id' => array(
				'type' => 'select',
				'choices' => function() {
					 return ORM::factory('user')
						->find_all()
						->as_array('id', 'username');
				}
			),
		);
	}

	public function before_create()
	{
		$this->created_by_id = Auth::get_id();
		$this->updated_by_id = $this->created_by_id;

		if (empty($this->status_id))
		{
			$this->status_id = Config::get('site', 'default_status_id');
		}

		if ($this->status_id == Model_Page::STATUS_PUBLISHED)
		{
			$this->published_on = date('Y-m-d H:i:s');
		}

		if (empty($this->use_redirect))
		{
			$this->redirect_url = NULL;
		}

		if ($this->position == 0)
		{
			$last_position = DB::select(array(DB::expr('MAX(position)'), 'pos'))
				->from($this->table_name())
				->where('parent_id', '=', $this->parent_id)
				->execute()
				->get('pos', 0);

			$this->position = ((int) $last_position) + 1;
		}

		Observer::notify('page_add_before_save', $this);

		return TRUE;
	}

	public function after_create()
	{
		$page = DB::select('id')
			->from($this->table_name())
			->where('id', '!=', $this->id)
			->where('slug', '=', $this->slug)
			->where('parent_id', '=', $this->parent_id)
			->execute()
			->get('id');

		if ($page !== NULL)
		{
			$this->slug = $this->slug . '-' . $this->id;
			$this->update();
		}

		Kohana::$log->add(Log::INFO, 'Page :id added by :user', array(
			':id' => $this->id
		))->write();

		Observer::notify('page_add_after_save', $this);

		return TRUE;
	}

	public function before_update()
	{
		if (empty($this->published_on) AND $this->status_id == Model_Page::STATUS_PUBLISHED)
		{
			$this->published_on = date('Y-m-d H:i:s');
		}

		if (empty($this->use_redirect))
		{
			$this->redirect_url = NULL;
		}

		// Если запрещены теги в Заголовке, удаляем их
		if (Config::get('site', 'allow_html_title') == Config::NO)
		{
			$this->title = strip_tags(trim($this->title));
		}

		$this->updated_by_id = Auth::get_id();

		Observer::notify('page_edit_before_save', $this);

		return TRUE;
	}

	public function after_update()
	{
		if (Kohana::$caching === TRUE)
		{
			Cache::instance()->delete_tag('pages');
		}

		Kohana::$log->add(Log::INFO, 'Page :id edited by :user', array(
			':id' => $this->id
		))->write();

		Observer::notify('page_edit_after_save', $this);

		return $this->after_create();
	}

	public function before_delete()
	{
		Observer::notify('page_before_delete', $this);

		$this->delete_children();

		return TRUE;
	}
	
	public function after_delete($id)
	{
		Kohana::$log->add(Log::INFO, 'Page :id deleted by :user', array(
			':id' => $id
		))->write();

		Observer::notify('page_after_delete', $id);

		if (Kohana::$caching === TRUE)
		{
			Cache::instance()->delete_tag('pages');
		}
	}

	/**
	 * Статус страницы
	 * 
	 * @return string
	 */
	public function get_status()
	{
		$status = __('none');
		$label = 'default';

		switch ($this->status_id)
		{
			case self::STATUS_DRAFT:
				$status = __('Draft');
				$label = 'info';
				break;
			case self::STATUS_PASSWORD_PROTECTED:
				$status = __('Password protected');
				$label = 'warning';
				break;
			case self::STATUS_HIDDEN:
				$status = __('Hidden');
				break;
			case self::STATUS_PUBLISHED:
				if (strtotime($this->published_on) > time())
				{
					$status = __('Pending');
				}
				else
				{
					$status = __('Published');
				}
				
				$label = 'success';
				break;
		}

		return UI::label($status, $label . ' editable-status', array('data-value' => $this->status_id));
	}

	/**
	 * Получение ссылки на страницу
	 * 
	 * @return string
	 */
	public function get_public_anchor()
	{
		return HTML::anchor($this->get_frontend_url(), UI::label(UI::icon('globe') . ' ' . __('View page')), array(
			'class' => 'item-preview', 'target' => '_blank'
		));
	}

	/**
	 * 
	 * 
	 * @return string
	 */
	public function get_uri()
	{
		if ($this->parent->loaded())
		{
			$result = $this->parent->get_uri() . '/' . $this->slug;
		}
		else
		{
			$result = $this->slug;
		}

		return $result;
	}

	/**
	 * @return string
	 */
	public function get_frontend_url()
	{
		return URL::frontend($this->get_uri(), TRUE);
	}

	/**
	 * Получение ссылки на редактирование страницы
	 * @return string
	 */
	public function get_url()
	{
		return Route::get('backend')->uri(array(
			'controller' => 'page',
			'action' => 'edit',
			'id' => $this->id
		));
	}

	/**
	 * Получение названия шаблона текущей страницы
	 * @return string
	 */
	public function layout()
	{
		if (empty($this->layout_file) AND $this->parent->loaded())
		{
			return $this->parent->layout();
		}

		return $this->layout_file;
	}

	/**
	 * 
	 * @param string $keyword
	 * @return ORM
	 */
	public function like($keyword)
	{
		return $this
			->where_open()
			->or_where(DB::expr('LOWER(title)'), 'like', '%:query%')
			->or_where('slug', 'like', '%:query%')
			->or_where('breadcrumb', 'like', '%:query%')
			->or_where('meta_title', 'like', '%:query%')
			->or_where('meta_keywords', 'like', '%:query%')
			->where_close()
			->param(':query', DB::expr($keyword));
	}

	/**
	 * 
	 * @param integer $id
	 * @return ORM
	 */
	public function children_of($id)
	{
		return $this
			->where('parent_id', '=', (int) $id)
			->order_by('position', 'asc')
			->order_by('page.created_on', 'asc');
	}

	/**
	 * Проверка на существование внутренних страницы
	 * 
	 * @return boolean
	 */
	public function has_children()
	{
		return (bool) DB::select(array(DB::expr('COUNT(*)'), 'total'))
			->from($this->table_name())
			->where('parent_id', '=', $this->id)
			->execute()
			->get('total');
	}

	/**
	 * Удаление внутренних страниц
	 * 
	 * @return \KodiCMS_Model_Page
	 */
	public function delete_children()
	{
		$child_pages = ORM::factory('page')
			->where('parent_id', '=', $this->id)
			->find_all();

		foreach ($child_pages as $page)
		{
			$page->delete();
		}

		return $this;
	}

	/**
	 * Получения списка ролей, с которыми разрешен доступ к странице
	 * 
	 * @param boolean $all
	 * @return array
	 */
	public function get_permissions($all = FALSE)
	{
		if (!$this->loaded() OR $all === TRUE)
		{
			$roles = ORM::factory('role')
				->where('name', 'in', array('administrator', 'developer', 'editor'));
		}
		else
		{
			$roles = $this->roles;
		}

		return $roles->find_all()->as_array('id', 'name');
	}

	/**
	 * Обновление списка ролей, с которым разрешен доступ к странице
	 * 
	 * @param array $permissions
	 * @return ORM
	 */
	public function save_permissions(array $permissions = NULL)
	{
		if (empty($permissions))
		{
			$permissions = array_keys($this->get_permissions(TRUE));
		}

		return $this->update_related_ids('roles', $permissions);
	}

	/**
	 * 
	 * @return array
	 */
	public function as_array()
	{
		$object = parent::as_array();

		$object['layout'] = $this->layout();

		return $object;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function behavior()
	{
		if (!Valid::not_empty($this->behavior_id))
		{
			return NULL;
		}

		return Behavior::factory($this->behavior_id);
	}

	/**
	 * 
	 * @param string $slug
	 * @return string
	 */
	public function clean_slug($slug)
	{
		$ext = pathinfo($slug, PATHINFO_EXTENSION);
		$slug = pathinfo($slug, PATHINFO_FILENAME);

		$slug = URL::title($slug);

		if (!empty($ext) AND File::mime_by_ext($ext) !== FALSE AND URL_SUFFIX != '.' . $ext)
		{
			$slug .= '.' . $ext;
		}

		return $slug;
	}

	/**
	 * Получение списка страниц за исключением текущей
	 * 
	 * @return array
	 */
	public function get_sitemap()
	{
		$sitemap = Model_Page_Sitemap::get(TRUE);
		if ($this->loaded())
		{
			$sitemap->exclude(array($this->id));
		}

		return $sitemap->select_choices();
	}

	/**
	 * Получение списка шаблонов
	 * 
	 * @return array
	 */
	public function get_layouts_list()
	{
		$options = array();

		if ($this->id != 1)
		{
			$layout = NULL;
			if ($this->parent->loaded())
			{
				$layout = $this->parent->layout();
			}

			if (empty($layout))
			{
				$layout = __('--- Not set ---');
			}

			$options[0] = __('--- inherit (:layout) ---', array(':layout' => $layout));
		}
		else
		{
			$options[0] = __('--- Not set ---');
		}


		$layouts = Model_File_Layout::find_all();

		foreach ($layouts as $layout)
		{
			$options[$layout->name] = $layout->name;
		}

		return $options;
	}

	/**
	 * 
	 * @param array $ids
	 * @param type $date
	 */
	public function set_update_date(array $ids, $date = NULL)
	{
		if (empty($ids))
		{
			return FALSE;
		}

		if ($date === NULL OR ! Valid::date($date))
		{
			$date = date('Y-m-d H:i:s');
		}

		DB::update($this->table_name())
			->set(array(
				'updated_on' => $date
			))
			->where('id', 'in', $ids)
			->execute($this->_db);

		if (Kohana::$caching === TRUE)
		{
			Cache::instance()->delete_tag('pages');
		}
		
		return TRUE;
	}

} // end Model_Page class