<?php defined('SYSPATH') OR die('No direct script access.');

class Feed extends Kohana_Feed {
	
	/**
	 * Parses a remote feed into an array.
	 *
	 * @param   string  $feed   remote feed URL
	 * @param   integer $limit  item limit to fetch
	 * @return  array
	 */
	public static function parse($feed, $limit = 0)
	{
		// Check if SimpleXML is installed
		if (!function_exists('simplexml_load_file'))
		{
			throw new Kohana_Exception('SimpleXML must be installed!');
		}

		// Make limit an integer
		$limit = (int) $limit;

		// Disable error reporting while opening the feed
		$error_level = error_reporting(0);

		// Allow loading by filename or raw XML string
		if (Valid::url($feed))
		{
			// Use native Request client to get remote contents
			$response = Request::factory($feed)->execute();
			$feed = $response->body();
		}
		elseif (is_file($feed))
		{
			// Get file contents
			$feed = file_get_contents($feed);
		}

		// Load the feed
		$feed = simplexml_load_string($feed, 'SimpleXMLElement', LIBXML_NOCDATA);

		// Restore error reporting
		error_reporting($error_level);

		// Feed could not be loaded
		if ($feed === FALSE)
		{
			return array();
		}

		$namespaces = $feed->getNamespaces(TRUE);

		// Detect the feed type. RSS 1.0/2.0 and Atom 1.0 are supported.
		$entries = isset($feed->channel) ? $feed->xpath('//item') : $feed->entry;

		$i = 0;
		$items = array();

		foreach ($entries as $item)
		{
			if ($limit > 0 AND $i++ === $limit)
			{
				break;
			}
	
			$item_fields = (array) $item;

			// get namespaced tags
			foreach ($namespaces as $ns)
			{
				$item_fields += (array) $item->children($ns);
			}

			$items[] = $item_fields;
		}

		return array(
			'feed_title' => isset($feed->channel) ? (string) $feed->channel->title : (string) $feed->feed->title,
			'feed_link' => isset($feed->channel) ? (string) $feed->channel->link : (string) $feed->feed->link,
			'feed_image' => isset($feed->channel) ? (string) $feed->channel->image->url : (string) $feed->feed->logo->url,
			'items' => $items
		);
	}
	
}
