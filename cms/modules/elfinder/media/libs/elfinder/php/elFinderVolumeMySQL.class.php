<?php

/**
 * Simple elFinder driver for MySQL.
 *
 * @author Dmitry (dio) Levashov
 * */
class elFinderVolumeMySQL extends elFinderVolumeDriver {

	/**
	 * Driver id
	 * Must be started from letter and contains [a-z0-9]
	 * Used as part of volume id
	 *
	 * @var string
	 * */
	protected $driverId = 'm';

	/**
	 * Database object
	 *
	 * @var Database
	 * */
	protected $db = null;

	/**
	 * Tables to store files
	 *
	 * @var string
	 * */
	protected $tbf = '';

	/**
	 * Directory for tmp files
	 * If not set driver will try to use tmbDir as tmpDir
	 *
	 * @var string
	 * */
	protected $tmpPath = '';

	/**
	 * Numbers of sql requests (for debug)
	 *
	 * @var int
	 * */
	protected $sqlCnt = 0;

	/**
	 * Last db error message
	 *
	 * @var string
	 * */
	protected $dbError = '';

	/**
	 * Constructor
	 * Extend options with required fields
	 *
	 * @return void
	 * @author Dmitry (dio) Levashov
	 * */
	public function __construct()
	{
		$opts = array(
			'host' => 'localhost',
			'user' => 'root',
			'pass' => '',
			'db' => '',
			'port' => null,
			'socket' => null,
			'files_table' => 'elfinder_file',
			'tmbPath' => '',
			'tmpPath' => ''
		);
		$this->options = array_merge($this->options, $opts);
		$this->options['mimeDetect'] = 'internal';
	}

	/*	 * ****************************************************************** */
	/*                        INIT AND CONFIGURE                         */
	/*	 * ****************************************************************** */

	/**
	 * Prepare driver before mount volume.
	 * Connect to db, check required tables and fetch root path
	 *
	 * @return bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function init()
	{
		if (!$this->options['host'] || !$this->options['user'] || !$this->options['db'] || !$this->options['path'] || !$this->options['files_table'])
		{
			return false;
		}

		$this->tbf = $this->options['files_table'];
		
		$this->db = Database::instance();

		$this->updateCache($this->options['path'], $this->_stat($this->options['path']));

		return true;
	}

	/**
	 * Set tmp path
	 *
	 * @return void
	 * @author Dmitry (dio) Levashov
	 * */
	protected function configure()
	{
		parent::configure();

		if (($tmp = $this->options['tmpPath']))
		{
			if (!file_exists($tmp))
			{
				if (@mkdir($tmp))
				{
					@chmod($tmp, $this->options['tmbPathMode']);
				}
			}

			$this->tmpPath = is_dir($tmp) && is_writable($tmp) ? $tmp : false;
		}

		if (!$this->tmpPath && $this->tmbPath && $this->tmbPathWritable)
		{
			$this->tmpPath = $this->tmbPath;
		}

		$this->mimeDetect = 'internal';
	}

	/**
	 * Close connection
	 *
	 * @return void
	 * @author Dmitry (dio) Levashov
	 * */
	public function umount()
	{
	}

	/**
	 * Return debug info for client
	 *
	 * @return array
	 * @author Dmitry (dio) Levashov
	 * */
	public function debug()
	{
		$debug = parent::debug();
		$debug['sqlCount'] = $this->sqlCnt;
		if ($this->dbError)
		{
			$debug['dbError'] = $this->dbError;
		}
		return $debug;
	}

	/**
	 * Perform sql query and return result.
	 * Increase sqlCnt and save error if occured
	 *
	 * @param  string  $sql  query
	 * @return misc
	 * @author Dmitry (dio) Levashov
	 * */
	protected function query($type, $sql)
	{
		return $this->db->query($type, $sql);
	}

	/**
	 * Create empty object with required mimetype
	 *
	 * @param  string  $path  parent dir path
	 * @param  string  $name  object name
	 * @param  string  $mime  mime type
	 * @return bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function make($path, $name, $mime)
	{
		$data = array(
			'parent_id' => $path, 
			'name' => $name, 
			'size' => 0, 
			'mtime' => time(), 
			'mime' =>  $mime,
			'content' => '', 
			'read' => $this->defaults['read'],
			'write' => $this->defaults['write']
		);

		$result = (bool) DB::insert($this->tbf, array_keys($data))->values($data)->execute();

		return $result;
	}

	/**
	 * Search files
	 *
	 * @param  string  $q  search string
	 * @param  array   $mimes
	 * @return array
	 * @author Dmitry (dio) Levashov
	 * */
	public function search($q, $mimes)
	{
		$result = array();

		$res = DB::select('*', array('0', 'dirs'), array('mtime', 'ts'))->from($this->tbf)->where('name', 'RLIKE', $q)->execute();

		foreach ($res as $row)
		{
			if ($this->mimeAccepted($row['mime'], $mimes))
			{
				$id = $row['id'];
				if ($row['parent_id'])
				{
					$row['phash'] = $this->encode($row['parent_id']);
				}

				if ($row['mime'] == 'directory')
				{
					unset($row['width']);
					unset($row['height']);
				}
				else
				{
					unset($row['dirs']);
				}

				unset($row['id']);
				unset($row['parent_id']);



				if (($stat = $this->updateCache($id, $row)) && empty($stat['hidden']))
				{
					$result[] = $stat;
				}
			}
		}

		return $result;
	}

	/**
	 * Return temporary file path for required file
	 *
	 * @param  string  $path   file path
	 * @return string
	 * @author Dmitry (dio) Levashov
	 * */
	protected function tmpname($path)
	{
		return $this->tmpPath . DIRECTORY_SEPARATOR . md5($path);
	}

	/**
	 * Resize image
	 *
	 * @param  string   $hash    image file
	 * @param  int      $width   new width
	 * @param  int      $height  new height
	 * @param  bool     $crop    crop image
	 * @return array|false
	 * @author Dmitry (dio) Levashov
	 * @author Alexey Sukhotin
	 * */
	public function resize($hash, $width, $height, $x, $y, $mode = 'resize', $bg = '', $degree = 0)
	{
		if ($this->commandDisabled('resize'))
		{
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		if (($file = $this->file($hash)) == false)
		{
			return $this->setError(elFinder::ERROR_FILE_NOT_FOUND);
		}

		if (!$file['write'] || !$file['read'])
		{
			return $this->setError(elFinder::ERROR_PERM_DENIED);
		}

		$path = $this->decode($hash);

		if (!$this->canResize($path, $file))
		{
			return $this->setError(elFinder::ERROR_UNSUPPORT_TYPE);
		}

		$img = $this->tmpname($path);

		if (!($fp = @fopen($img, 'w+')))
		{
			return false;
		}

		$content = DB::select('content')->from($this->tbf)->where('id', '=', $path)->execute()->get('content');
		if ($content !== NULL)
		{
			fwrite($fp, $content);
			rewind($fp);
			fclose($fp);
		}
		else
		{
			return false;
		}


		switch ($mode)
		{
			case 'propresize':
				$result = $this->imgResize($img, $width, $height, true, true);
				break;

			case 'crop':
				$result = $this->imgCrop($img, $width, $height, $x, $y);
				break;

			case 'fitsquare':
				$result = $this->imgSquareFit($img, $width, $height, 'center', 'middle', $bg ? $bg : $this->options['tmbBgColor']);
				break;

			default:
				$result = $this->imgResize($img, $width, $height, false, true);
				break;
		}

		if ($result)
		{
			$content = file_get_contents($img);
			$query = (bool) DB::update($this->tbf)->set(array(
				'content' => $content,
				'mtime' => DB::expr('UNIX_TIMESTAMP()')
			))->where('id', '=', $path)->execute();

			if ( ! $query)
			{
				@unlink($img);
				return false;
			}

			@unlink($img);
			$this->rmTmb($file);
			$this->clearcache();
			return $this->stat($path);
		}

		return false;
	}

	/*	 * ****************************************************************** */
	/*                               FS API                              */
	/*	 * ****************************************************************** */

	/**
	 * Cache dir contents
	 *
	 * @param  string  $path  dir path
	 * @return void
	 * @author Dmitry Levashov
	 * */
	protected function cacheDir($path)
	{
		$this->dirsCache[$path] = array();

		$res = DB::select('f.*', array(DB::expr('IF(ch.id, 1, 0)'), 'dirs'), array('f.mtime', 'ts'))
			->from(array($this->tbf, 'f'))
			->join(array($this->tbf, 'ch'), 'left')
				->on('ch.parent_id', '=', 'f.id')
				->on('ch.mime', '=', DB::expr('"directory"'))
			->where('f.parent_id', '=', $path)
			->group_by('f.id')
			->execute();

		foreach ($res as $row)
		{
			// debug($row);
			$id = $row['id'];
			if ($row['parent_id'])
			{
				$row['phash'] = $this->encode($row['parent_id']);
			}

			if ($row['mime'] == 'directory')
			{
				unset($row['width']);
				unset($row['height']);
			}
			else
			{
				unset($row['dirs']);
			}

			unset($row['id']);
			unset($row['parent_id']);



			if (($stat = $this->updateCache($id, $row)) && empty($stat['hidden']))
			{
				$this->dirsCache[$path][] = $id;
			}
		}

		return $this->dirsCache[$path];
	}

	/**
	 * Return array of parents paths (ids)
	 *
	 * @param  int   $path  file path (id)
	 * @return array
	 * @author Dmitry (dio) Levashov
	 * */
	protected function getParents($path)
	{
		$parents = array();

		while ($path)
		{
			if ($file = $this->stat($path))
			{
				array_unshift($parents, $path);
				$path = isset($file['phash']) ? $this->decode($file['phash']) : false;
			}
		}

		if (count($parents))
		{
			array_pop($parents);
		}
		return $parents;
	}

	/**
	 * Return correct file path for LOAD_FILE method
	 *
	 * @param  string $path  file path (id)
	 * @return string
	 * @author Troex Nevelin
	 * */
	protected function loadFilePath($path)
	{
		$realPath = realpath($path);
		if (DIRECTORY_SEPARATOR == '\\')
		{ // windows
			$realPath = str_replace('\\', '\\\\', $realPath);
		}
		return $this->db->escape($realPath);
	}

	/**
	 * Recursive files search
	 *
	 * @param  string  $path   dir path
	 * @param  string  $q      search string
	 * @param  array   $mimes
	 * @return array
	 * @author Dmitry (dio) Levashov
	 * */
	protected function doSearch($path, $q, $mimes)
	{
		return array();
	}

	/*	 * ********************* paths/urls ************************ */

	/**
	 * Return parent directory path
	 *
	 * @param  string  $path  file path
	 * @return string
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _dirname($path)
	{
		return ($stat = $this->stat($path)) ? ($stat['phash'] ? $this->decode($stat['phash']) : $this->root) : false;
	}

	/**
	 * Return file name
	 *
	 * @param  string  $path  file path
	 * @return string
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _basename($path)
	{
		return ($stat = $this->stat($path)) ? $stat['name'] : false;
	}

	/**
	 * Join dir name and file name and return full path
	 *
	 * @param  string  $dir
	 * @param  string  $name
	 * @return string
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _joinPath($dir, $name)
	{
		$res = DB::select('id')->from($this->tbf)->where('parent_id', '=', $dir)->where('name', '=', $name)->execute()->current();

		if ($res !== NULL)
		{
			$this->updateCache($r['id'], $this->_stat($r['id']));
			return $r['id'];
		}
		return -1;
	}

	/**
	 * Return normalized path, this works the same as os.path.normpath() in Python
	 *
	 * @param  string  $path  path
	 * @return string
	 * @author Troex Nevelin
	 * */
	protected function _normpath($path)
	{
		return $path;
	}

	/**
	 * Return file path related to root dir
	 *
	 * @param  string  $path  file path
	 * @return string
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _relpath($path)
	{
		return $path;
	}

	/**
	 * Convert path related to root dir into real path
	 *
	 * @param  string  $path  file path
	 * @return string
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _abspath($path)
	{
		return $path;
	}

	/**
	 * Return fake path started from root dir
	 *
	 * @param  string  $path  file path
	 * @return string
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _path($path)
	{
		if (($file = $this->stat($path)) == false)
		{
			return '';
		}

		$parentsIds = $this->getParents($path);
		$path = '';
		foreach ($parentsIds as $id)
		{
			$dir = $this->stat($id);
			$path .= $dir['name'] . $this->separator;
		}
		return $path . $file['name'];
	}

	/**
	 * Return true if $path is children of $parent
	 *
	 * @param  string  $path    path to check
	 * @param  string  $parent  parent path
	 * @return bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _inpath($path, $parent)
	{
		return $path == $parent ? true : in_array($parent, $this->getParents($path));
	}

	/*	 * *************** file stat ******************* */

	/**
	 * Return stat for given path.
	 * Stat contains following fields:
	 * - (int)    size    file size in b. required
	 * - (int)    ts      file modification time in unix time. required
	 * - (string) mime    mimetype. required for folders, others - optionally
	 * - (bool)   read    read permissions. required
	 * - (bool)   write   write permissions. required
	 * - (bool)   locked  is object locked. optionally
	 * - (bool)   hidden  is object hidden. optionally
	 * - (string) alias   for symlinks - link target path relative to root path. optionally
	 * - (string) target  for symlinks - link target path. optionally
	 *
	 * If file does not exists - returns empty array or false.
	 *
	 * @param  string  $path    file path 
	 * @return array|false
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _stat($path)
	{
		$res = DB::select('f.*', array(DB::expr('IF(ch.id, 1, 0)'), 'dirs'), array('f.mtime', 'ts'))
			->from(array($this->tbf, 'f'))
			->join(array($this->tbf, 'p'), 'left')
				->on('p.id', '=', 'f.parent_id')
			->join(array($this->tbf, 'ch'), 'left')
				->on('ch.parent_id', '=', 'f.id')
				->on('ch.mime', '=', DB::expr('"directory"'))
			->where('f.id', '=', $path)
			->group_by('f.id')
			->execute()
			->current();

		if ($res !== NULL)
		{
			$stat = $res;
			if ($stat['parent_id'])
			{
				$stat['phash'] = $this->encode($stat['parent_id']);
			}
			if ($stat['mime'] == 'directory')
			{
				unset($stat['width']);
				unset($stat['height']);
			}
			else
			{
				unset($stat['dirs']);
			}
			unset($stat['id']);
			unset($stat['parent_id']);
			return $stat;
		}
		return array();
	}

	/**
	 * Return true if path is dir and has at least one childs directory
	 *
	 * @param  string  $path  dir path
	 * @return bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _subdirs($path)
	{
		return ($stat = $this->stat($path)) && isset($stat['dirs']) ? $stat['dirs'] : false;
	}

	/**
	 * Return object width and height
	 * Usualy used for images, but can be realize for video etc...
	 *
	 * @param  string  $path  file path
	 * @param  string  $mime  file mime type
	 * @return string
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _dimensions($path, $mime)
	{
		return ($stat = $this->stat($path)) && isset($stat['width']) && isset($stat['height']) ? $stat['width'] . 'x' . $stat['height'] : '';
	}

	/*	 * ****************** file/dir content ******************** */

	/**
	 * Return files list in directory.
	 *
	 * @param  string  $path  dir path
	 * @return array
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _scandir($path)
	{
		return isset($this->dirsCache[$path]) ? $this->dirsCache[$path] : $this->cacheDir($path);
	}

	/**
	 * Open file and return file pointer
	 *
	 * @param  string  $path  file path
	 * @param  string  $mode  open file mode (ignored in this driver)
	 * @return resource|false
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _fopen($path, $mode = 'rb')
	{
		$fp = $this->tmbPath ? @fopen($this->tmpname($path), 'w+') : @tmpfile();


		if ($fp)
		{
			$content = DB::select('content')->from($this->tbf)->where('id', '=', $path)->execute()->get('content');
			if ($content !== NULL)
			{
				fwrite($fp, $content);
				rewind($fp);
				return $fp;
			}
			else
			{
				$this->_fclose($fp, $path);
			}
		}

		return false;
	}

	/**
	 * Close opened file
	 *
	 * @param  resource  $fp  file pointer
	 * @return bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _fclose($fp, $path = '')
	{
		@fclose($fp);
		if ($path)
		{
			@unlink($this->tmpname($path));
		}
	}

	/*	 * ******************  file/dir manipulations ************************ */

	/**
	 * Create dir and return created dir path or false on failed
	 *
	 * @param  string  $path  parent dir path
	 * @param string  $name  new directory name
	 * @return string|bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _mkdir($path, $name)
	{
		return $this->make($path, $name, 'directory') ? $this->_joinPath($path, $name) : false;
	}

	/**
	 * Create file and return it's path or false on failed
	 *
	 * @param  string  $path  parent dir path
	 * @param string  $name  new file name
	 * @return string|bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _mkfile($path, $name)
	{
		return $this->make($path, $name, 'text/plain') ? $this->_joinPath($path, $name) : false;
	}

	/**
	 * Create symlink. FTP driver does not support symlinks.
	 *
	 * @param  string  $target  link target
	 * @param  string  $path    symlink path
	 * @return bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _symlink($target, $path, $name)
	{
		return false;
	}

	/**
	 * Copy file into another file
	 *
	 * @param  string  $source     source file path
	 * @param  string  $targetDir  target directory path
	 * @param  string  $name       new file name
	 * @return bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _copy($source, $targetDir, $name)
	{
		$this->clearcache();
		$id = $this->_joinPath($targetDir, $name);

		$sql = $id > 0 
				? sprintf('REPLACE INTO %s (id, parent_id, name, content, size, mtime, mime, width, height, `read`, `write`, `locked`, `hidden`) (SELECT %d, %d, name, content, size, mtime, mime, width, height, `read`, `write`, `locked`, `hidden` FROM %s WHERE id=%d)', $this->tbf, $id, $this->_dirname($id), $this->tbf, $source) 
				: sprintf('INSERT INTO %s (parent_id, name, content, size, mtime, mime, width, height, `read`, `write`, `locked`, `hidden`) SELECT %d, "%s", content, size, %d, mime, width, height, `read`, `write`, `locked`, `hidden` FROM %s WHERE id=%d', $this->tbf, $targetDir, $this->db->escape($name), time(), $this->tbf, $source);

		return $this->query(Database::INSERT, $sql);
	}

	/**
	 * Move file into another parent dir.
	 * Return new file path or false.
	 *
	 * @param  string  $source  source file path
	 * @param  string  $target  target dir path
	 * @param  string  $name    file name
	 * @return string|bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _move($source, $targetDir, $name)
	{
		$res = DB::update($this->tbf)->set(array(
			'parent_id' => $targetDir,
			'name' => $name
		))->where('id', '=', $source)
		->execute();
		
		return (bool) $res ? $source : false;
	}

	/**
	 * Remove file
	 *
	 * @param  string  $path  file path
	 * @return bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _unlink($path)
	{
		return (bool) DB::delete()->from($this->tbf)->where('id', '=', $path)->where('mime', '!=', 'directory')->limit(1)->execute();
	}

	/**
	 * Remove dir
	 *
	 * @param  string  $path  dir path
	 * @return bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _rmdir($path)
	{
		return (bool) DB::delete()->from($this->tbf)->where('id', '=', $path)->where('mime', '=', 'directory')->limit(1)->execute();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Dmitry Levashov
	 * */
	protected function _setContent($path, $fp)
	{
		rewind($fp);
		$fstat = fstat($fp);
		$size = $fstat['size'];
	}

	/**
	 * Create new file and write into it from file pointer.
	 * Return new file path or false on error.
	 *
	 * @param  resource  $fp   file pointer
	 * @param  string    $dir  target dir path
	 * @param  string    $name file name
	 * @param  array     $stat file stat (required by some virtual fs)
	 * @return bool|string
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _save($fp, $dir, $name, $stat)
	{
		$this->clearcache();

		$mime = $stat['mime'];
		$w = !empty($stat['width']) ? $stat['width'] : 0;
		$h = !empty($stat['height']) ? $stat['height'] : 0;

		$id = $this->_joinPath($dir, $name);
		rewind($fp);
		$stat = fstat($fp);
		$size = $stat['size'];

		if (($tmpfile = tempnam($this->tmpPath, $this->id)))
		{
			if (($trgfp = fopen($tmpfile, 'wb')) == false)
			{
				unlink($tmpfile);
			}
			else
			{
				$content = '';
				while (!feof($fp))
				{
					$content .= fread($fp, 8192);
					fwrite($trgfp, fread($fp, 8192));
				}
				fclose($trgfp);

				$sql = $id > 0 
						? 'REPLACE INTO %s (id, parent_id, name, content, size, mtime, mime, width, height) VALUES (' . $id . ', %d, "%s", "%s", %d, %d, "%s", %d, %d)' 
						: 'INSERT INTO %s (parent_id, name, content, size, mtime, mime, width, height) VALUES (%d, "%s", "%s", %d, %d, "%s", %d, %d)';
				
				$sql = sprintf($sql, $this->tbf, $dir, $this->db->escape($name), $this->db->escape($content), $size, time(), $mime, $w, $h);

				$res = $this->query(Database::INSERT, $sql);
				unlink($tmpfile);

				if ($res)
				{
					return $id > 0 ? $id : $res[0];
				}
			}
		}


		$content = '';
		rewind($fp);
		while (!feof($fp))
		{
			$content .= fread($fp, 8192);
		}

		$sql = $id > 0 
				? 'REPLACE INTO %s (id, parent_id, name, content, size, mtime, mime, width, height) VALUES (' . $id . ', %d, "%s", "%s", %d, %d, "%s", %d, %d)' 
				: 'INSERT INTO %s (parent_id, name, content, size, mtime, mime, width, height) VALUES (%d, "%s", "%s", %d, %d, "%s", %d, %d)';

		$sql = sprintf($sql, $this->tbf, $dir, $this->db->escape($name), $this->db->escape($content), $size, time(), $mime, $w, $h);

		unset($content);
		$res = $this->query(Database::INSERT, $sql);
		if ($res)
		{
			return $id > 0 ? $id : $res[0];
		}

		return false;
	}

	/**
	 * Get file contents
	 *
	 * @param  string  $path  file path
	 * @return string|false
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _getContents($path)
	{
		$content = DB::select('content')->from($this->tbf)->where('id', '=', $path)->execute()->get('content');
		return $content !== NULL ? $r['content'] : false;
	}

	/**
	 * Write a string to a file
	 *
	 * @param  string  $path     file path
	 * @param  string  $content  new file content
	 * @return bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _filePutContents($path, $content)
	{
		return (bool) DB::update($this->tbf)->set(array(
			'content' => $content,
			'size' => strlen($content),
			'mtime' => time()
		))->where('id', '=', $path)->execute();
	}

	/**
	 * Detect available archivers
	 *
	 * @return void
	 * */
	protected function _checkArchivers()
	{
		return;
	}

	/**
	 * Unpack archive
	 *
	 * @param  string  $path  archive path
	 * @param  array   $arc   archiver command and arguments (same as in $this->archivers)
	 * @return void
	 * @author Dmitry (dio) Levashov
	 * @author Alexey Sukhotin
	 * */
	protected function _unpack($path, $arc)
	{
		return;
	}

	/**
	 * Recursive symlinks search
	 *
	 * @param  string  $path  file/dir path
	 * @return bool
	 * @author Dmitry (dio) Levashov
	 * */
	protected function _findSymlinks($path)
	{
		return false;
	}

	/**
	 * Extract files from archive
	 *
	 * @param  string  $path  archive path
	 * @param  array   $arc   archiver command and arguments (same as in $this->archivers)
	 * @return true
	 * @author Dmitry (dio) Levashov, 
	 * @author Alexey Sukhotin
	 * */
	protected function _extract($path, $arc)
	{
		return false;
	}

	/**
	 * Create archive and return its path
	 *
	 * @param  string  $dir    target dir
	 * @param  array   $files  files names list
	 * @param  string  $name   archive name
	 * @param  array   $arc    archiver options
	 * @return string|bool
	 * @author Dmitry (dio) Levashov, 
	 * @author Alexey Sukhotin
	 * */
	protected function _archive($dir, $files, $name, $arc)
	{
		return false;
	}
}
