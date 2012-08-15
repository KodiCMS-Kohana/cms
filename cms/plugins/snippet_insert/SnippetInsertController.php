<?php if (!defined('CMS_ROOT')) die;

class SnippetInsertController extends PluginController
{
	public function snippets_json()
	{
		$snippets = Snippet::findAll();
		
		echo $this->renderJSON($snippets);
	}
	
	public function snippet_info_json($snippet_name)
	{
		$snippet_file = SNIPPETS_ROOT.DIRECTORY_SEPARATOR.$snippet_name.'.'.SNIPPETS_EXT;
		
		$result = array();
		
		if (file_exists($snippet_file))
		{
			$file_content = file_get_contents($snippet_file);
			
			preg_match_all('/\/\*\*[\n\t\s\r]*\*[\n\t\s\r]*(.*)[\n\t\s\r]*\*[\n\t\s\r]*\@var[\n\t\s\r]*(int|float|string|bool|array|object)[\n\t\s\r]*\*\/[\n\t\s\r]*\$([a-zA-Z0-9_]*)[\n\t\s\r]*\=/', $file_content, $m, PREG_SET_ORDER);
			
			foreach ($m as $match)
			{
				$result[] = array(
					'desc' => trim($match[1]),
					'type' => trim($match[2]),
					'name' => trim($match[3])
				);
			}
		}
		
		echo $this->renderJSON($result);
	}
}