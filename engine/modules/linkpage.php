<?php
/*
=============================================
 Name      : MWS Link Page v1.1
 Author    : Mehmet Hanoğlu ( MaRZoCHi )
 Site      : https://dle.net.tr/
 License   : MIT License
 Date      : 28.09.2018
=============================================
*/

if (!defined('DATALIFEENGINE')) die("Hacking attempt");

include ENGINE_DIR . "/data/linkpage.conf.php";
include ROOT_DIR . "/language/" . $config['langs'] . "/linkpage.lng";

if (isset($_REQUEST['nid']) && is_numeric($_REQUEST['nid'])) {

	require_once (DLEPlugins::Check(ENGINE_DIR . '/classes/parse.class.php'));

	$stop_module = false;
	$err_module = [];

	$nid = intval($db->safesql($_REQUEST['nid']));
	$news = $db->super_query("SELECT p.id, p.title, p.short_story, p.full_story, p.category, p.date, p.autor, p.descr, p.keywords, p.xfields, p.alt_name, e.news_read  FROM " . PREFIX . "_post p LEFT JOIN " . PREFIX . "_post_extras e ON ( p.id = e.news_id ) WHERE CRC32(p.id) = {$nid}");

	define('NEWS_ID', $news['id']);

	if (!$news) {
		$stop_module = true;
		$err_module[] = $lang['mwslp_0'];
	}

	if (empty($news['xfields'])) {
		$stop_module = true;
		$err_module[] = $lang['mwslp_1'];
	}

	if (!$stop_module) {

		if ($config['allow_alt_url'] == "1") {
			if ($config['seo_type'] == 1 or $config['seo_type'] == 2) {
				if ($news['category'] and $config['seo_type'] == 2) {
					$news_link = $config['http_home_url'] . get_url($news['category']) . "/" . $news['id'] . "-" . $news['alt_name'] . ".html";
				} else {
					$news_link = $config['http_home_url'] . $news['id'] . "-" . $news['alt_name'] . ".html";
				}
			} else {
				$news_link = $config['http_home_url'] . date('Y/m/d/', $news['date']) . $news['alt_name'] . ".html";
			}
		} else {
			$news_link = $config['http_home_url'] . "index.php?newsid=" . $news['id'];
		}

		if (!$news['category']) {
			$my_cat = "---";
			$my_cat_link = "---";
		} else {
			if (!array_key_exists("category_separator", $config) || empty($config['category_separator'])) $config['category_separator'] = "&raquo;";
			$my_cat = array();
			$my_cat_link = array();
			$cat_list = explode(',', $news['category']);
			if ($config['category_separator'] != ',') $config['category_separator'] = ' ' . $config['category_separator'];
			if (count($cat_list) == 1 or ($view_template == "rss" and $config['rss_format'] == 2)) {
				$my_cat[] = $cat_info[$cat_list[0]]['name'];
				$my_cat_link = get_categories($cat_list[0], $config['category_separator']);
			} else {
				foreach ($cat_list as $element) {
					if ($element) {
						$my_cat[] = $cat_info[$element]['name'];
						if ($config['allow_alt_url']) $my_cat_link[] = "<a href=\"" . $config['http_home_url'] . get_url($element) . "/\">{$cat_info[$element]['name']}</a>";
						else $my_cat_link[] = "<a href=\"$PHP_SELF?do=cat&category={$cat_info[$element]['alt_name']}\">{$cat_info[$element]['name']}</a>";
					}
				}
				$my_cat_link = implode("{$config['category_separator']} ", $my_cat_link);
			}
			$my_cat = implode("{$config['category_separator']} ", $my_cat);
		}

		$from_news = false;
		if ((isset($_ENV['HTTP_REFERER']) and !empty($_ENV['HTTP_REFERER'])) or (isset($_SERVER['HTTP_REFERER']) and !empty($_SERVER['HTTP_REFERER']))) {
			$referer = (empty($_ENV['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $_ENV['HTTP_REFERER'];
			preg_match("#([0-9]+)\-(.*)\.html#", $referer, $matches);
			if ($matches) $referer_id = $matches[1];
			$from_news = ($referer_id == $news['id']) ? true : false;
		}

		$template_name = ($lpset['external_page']) ? "linkpage_external.tpl" : "linkpage.tpl";
		$tpl->load_template($template_name);

		$tpl->set("{news-views}", $news['news_read']);
		$tpl->set("{news-description}", $news['descr']);
		$tpl->set("{news-keywords}", $news['keywords']);
		$tpl->set("{news-title}", stripslashes($news['title']));
		$tpl->set("{news-author}", $news['autor']);
		$tpl->set("{news-date}", $news['date']);
		$tpl->set("{news-link}", $news_link);
		$tpl->set("{news-category}", $my_cat);
		$tpl->set("{news-category-link}", $my_cat_link);

		if (!defined('BANNERS')) {
			if ($config['allow_banner']) include_once ENGINE_DIR . '/modules/banners.php';
		}

		foreach ($banners as $name => $value) {
			$tpl->copy_template = str_replace("{banner_" . $name . "}", $value, $tpl->copy_template);
			if ($value) {
				$tpl->copy_template = str_replace("[banner_" . $name . "]", "", $tpl->copy_template);
				$tpl->copy_template = str_replace("[/banner_" . $name . "]", "", $tpl->copy_template);
			}
		}

		if (stripos($tpl->copy_template, "{custom") !== false) {
			$tpl->copy_template = preg_replace_callback("#\\{custom(.+?)\\}#i", "custom_print", $tpl->copy_template);
		}


		$tpl->set('{THEME}', $config['http_home_url'] . "templates/" . $config['skin']);

		$xfields = xfieldsload();
		$xfieldsdata = xfieldsdataload($news['xfields']);

		foreach ($xfields as $value) {
			$preg_safe_name = preg_quote($value[0], "'");

			$xfieldsdata[$value[0]] = stripslashes($xfieldsdata[$value[0]]);

			if ($value[20]) {

				$value[20] = explode(',', $value[20]);

				if ($value[20][0] and !in_array($member_id['user_group'], $value[20])) {
					$xfieldsdata[$value[0]] = "";
				}

			}

			if ($value[3] == "yesorno") {

				if (intval($xfieldsdata[$value[0]])) {
					$xfgiven = true;
					$xfieldsdata[$value[0]] = $lang['xfield_xyes'];
				} else {
					$xfgiven = false;
					$xfieldsdata[$value[0]] = $lang['xfield_xno'];
				}

			} else {

				if ($xfieldsdata[$value[0]] == "") $xfgiven = false;
				else $xfgiven = true;

			}

			if (!$xfgiven) {
				$tpl->copy_template = preg_replace("'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template);
				$tpl->copy_template = str_replace("[xfnotgiven_{$value[0]}]", "", $tpl->copy_template);
				$tpl->copy_template = str_replace("[/xfnotgiven_{$value[0]}]", "", $tpl->copy_template);
			} else {
				$tpl->copy_template = preg_replace("'\\[xfnotgiven_{$preg_safe_name}\\](.*?)\\[/xfnotgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template);
				$tpl->copy_template = str_replace("[xfgiven_{$value[0]}]", "", $tpl->copy_template);
				$tpl->copy_template = str_replace("[/xfgiven_{$value[0]}]", "", $tpl->copy_template);
			}

			if (strpos($tpl->copy_template, "[ifxfvalue") !== false) {
				$tpl->copy_template = preg_replace_callback("#\\[ifxfvalue(.+?)\\](.+?)\\[/ifxfvalue\\]#is", "check_xfvalue", $tpl->copy_template);
			}

			if ($value[6] and !empty($xfieldsdata[$value[0]])) {
				$temp_array = explode(",", $xfieldsdata[$value[0]]);
				$value3 = array();

				foreach ($temp_array as $value2) {

					$value2 = trim($value2);
					$value2 = str_replace("&#039;", "'", $value2);

					if ($config['allow_alt_url']) $value3[] = "<a href=\"" . $config['http_home_url'] . "xfsearch/" . $value[0] . "/" . urlencode($value2) . "/\">" . $value2 . "</a>";
					else $value3[] = "<a href=\"$PHP_SELF?do=xfsearch&amp;xfname=" . $value[0] . "&amp;xf=" . urlencode($value2) . "\">" . $value2 . "</a>";
				}

				if (empty($value[21])) $value[21] = ", ";

				$xfieldsdata[$value[0]] = implode($value[21], $value3);

				unset($temp_array);
				unset($value2);
				unset($value3);

			}

			if ($config['allow_links'] and $value[3] == "textarea" and function_exists('replace_links')) $xfieldsdata[$value[0]] = replace_links($xfieldsdata[$value[0]], $replace_links['news']);

			if ($value[3] == "image" and $xfieldsdata[$value[0]]) {
				$path_parts = @pathinfo($xfieldsdata[$value[0]]);

				if ($value[12] and file_exists(ROOT_DIR . "/uploads/posts/" . $path_parts['dirname'] . "/thumbs/" . $path_parts['basename'])) {
					$thumb_url = $config['http_home_url'] . "uploads/posts/" . $path_parts['dirname'] . "/thumbs/" . $path_parts['basename'];
					$img_url = $config['http_home_url'] . "uploads/posts/" . $path_parts['dirname'] . "/" . $path_parts['basename'];
				} else {
					$img_url = $config['http_home_url'] . "uploads/posts/" . $path_parts['dirname'] . "/" . $path_parts['basename'];
					$thumb_url = "";
				}

				if ($thumb_url) {
					$xfieldsdata[$value[0]] = "<a href=\"$img_url\" class=\"highslide\" target=\"_blank\"><img class=\"xfieldimage {$value[0]}\" src=\"$thumb_url\" alt=\"\"></a>";
				} else $xfieldsdata[$value[0]] = "<img class=\"xfieldimage {$value[0]}\" src=\"{$img_url}\" alt=\"\">";
			}

			if ($value[3] == "image") {
				if ($xfieldsdata[$value[0]]) {
					$tpl->copy_template = str_replace("[xfvalue_thumb_url_{$value[0]}]", $thumb_url, $tpl->copy_template);
					$tpl->copy_template = str_replace("[xfvalue_image_url_{$value[0]}]", $img_url, $tpl->copy_template);
				} else {
					$tpl->copy_template = str_replace("[xfvalue_thumb_url_{$value[0]}]", "", $tpl->copy_template);
					$tpl->copy_template = str_replace("[xfvalue_image_url_{$value[0]}]", "", $tpl->copy_template);
				}
			}

			if ($value[3] == "imagegalery" and $xfieldsdata[$value[0]] and stripos($tpl->copy_template, "[xfvalue_{$value[0]}]") !== false) {

				$fieldvalue_arr = explode(',', $xfieldsdata[$value[0]]);
				$gallery_image = array();
				$gallery_single_image = array();
				$xf_image_count = 0;
				$single_need = false;

				if (stripos($tpl->copy_template, "[xfvalue_{$value[0]} image=") !== false) $single_need = true;

				foreach ($fieldvalue_arr as $temp_value) {
					$xf_image_count++;

					$temp_value = trim($temp_value);

					if ($temp_value == "") continue;

					$path_parts = @pathinfo($temp_value);

					if ($value[12] and file_exists(ROOT_DIR . "/uploads/posts/" . $path_parts['dirname'] . "/thumbs/" . $path_parts['basename'])) {
						$thumb_url = $config['http_home_url'] . "uploads/posts/" . $path_parts['dirname'] . "/thumbs/" . $path_parts['basename'];
						$img_url = $config['http_home_url'] . "uploads/posts/" . $path_parts['dirname'] . "/" . $path_parts['basename'];
					} else {
						$img_url = $config['http_home_url'] . "uploads/posts/" . $path_parts['dirname'] . "/" . $path_parts['basename'];
						$thumb_url = "";
					}

					if ($thumb_url) {
						$gallery_image[] = "<li><a href=\"$img_url\" onclick=\"return hs.expand(this, { slideshowGroup: 'xf_" . NEWS_ID . "_{$value[0]}' })\" target=\"_blank\"><img src=\"{$thumb_url}\" alt=\"\"></a></li>";
						$gallery_single_image['[xfvalue_' . $value[0] . ' image="' . $xf_image_count . '"]'] = "<a href=\"{$img_url}\" class=\"highslide\" target=\"_blank\"><img class=\"xfieldimage {$value[0]}\" src=\"{$thumb_url}\" alt=\"\"></a>";
					} else {
						$gallery_image[] = "<li><img src=\"{$img_url}\" alt=\"\"></li>";
						$gallery_single_image['[xfvalue_' . $value[0] . ' image="' . $xf_image_count . '"]'] = "<img class=\"xfieldimage {$value[0]}\" src=\"{$img_url}\" alt=\"\">";
					}

				}

				if ($single_need and count($gallery_single_image)) {
					foreach ($gallery_single_image as $temp_key => $temp_value) $tpl->copy_template = str_replace($temp_key, $temp_value, $tpl->copy_template);
				}

				$xfieldsdata[$value[0]] = "<ul class=\"xfieldimagegallery {$value[0]}\">" . implode($gallery_image) . "</ul>";

			}

			$tpl->copy_template = str_replace("[xfvalue_{$value[0]}]", $xfieldsdata[$value[0]], $tpl->copy_template);

			if (preg_match("#\\[xfvalue_{$preg_safe_name} limit=['\"](.+?)['\"]\\]#i", $tpl->copy_template, $matches)) {
				$count = intval($matches[1]);

				$xfieldsdata[$value[0]] = str_replace("</p><p>", " ", $xfieldsdata[$value[0]]);
				$xfieldsdata[$value[0]] = strip_tags($xfieldsdata[$value[0]], "<br>");
				$xfieldsdata[$value[0]] = trim(str_replace("<br>", " ", str_replace("<br />", " ", str_replace("\n", " ", str_replace("\r", "", $xfieldsdata[$value[0]])))));

				if ($count and dle_strlen($xfieldsdata[$value[0]], $config['charset']) > $count) {

					$xfieldsdata[$value[0]] = dle_substr($xfieldsdata[$value[0]], 0, $count, $config['charset']);

					if (($temp_dmax = dle_strrpos($xfieldsdata[$value[0]], ' ', $config['charset']))) $xfieldsdata[$value[0]] = dle_substr($xfieldsdata[$value[0]], 0, $temp_dmax, $config['charset']);

				}

				$tpl->copy_template = str_replace($matches[0], $xfieldsdata[$value[0]], $tpl->copy_template);

			}

			if (stripos($tpl->copy_template, "[hide") !== false) {

				$tpl->copy_template = preg_replace_callback(
					"#\[hide(.*?)\](.+?)\[/hide\]#is",
					function ($matches) use ($member_id, $user_group, $lang) {

						$matches[1] = str_replace(array("=", " "), "", $matches[1]);
						$matches[2] = $matches[2];

						if ($matches[1]) {

							$groups = explode(',', $matches[1]);

							if (in_array($member_id['user_group'], $groups) or $member_id['user_group'] == "1") {
								return $matches[2];
							} else return "<div class=\"quote\">" . $lang['news_regus'] . "</div>";

						} else {

							if ($user_group[$member_id['user_group']]['allow_hide']) return $matches[2];
							else return "<div class=\"quote\">" . $lang['news_regus'] . "</div>";

						}

					},
					$tpl->copy_template
				);
			}


			if ($config['files_allow']) if (strpos($tpl->copy_template, "[attachment=") !== false) {
				$tpl->copy_template = show_attach($tpl->copy_template, NEWS_ID);
			}

		} // end-xfields

		foreach ($xfields as $xf) {
			if (strpos($xf[0], "_parts") !== false) {
				$xf_name = $xf[0];
				if (preg_match("#\\[" . $xf_name . "\\](.+?)\\[\/" . $xf_name . "\\]#is", $tpl->copy_template, $match)) {
					$part_tpl = trim($match[1]);
					$part_html = "";
					$parts = explode("\n", str_replace(["<br>", "<br />"], "\n", $xfieldsdata[$xf_name]));
					$parts = array_filter($parts);
					for ($x = 0; $x < count($parts); $x++) {
						$part_html .= str_replace(
							['{part-url}', '{part-counter}'],
							[$parts[$x], $x + 1],
							$part_tpl
						);
					}
					$tpl->copy_template = str_replace($match[0], $part_html, $tpl->copy_template);
				}
			}
		}

		if (stripos($tpl->copy_template, "{image-") !== false) {

			$images = array();
			preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $news['short_story'] . $news['xfields'], $media);
			$data = preg_replace('/(img|src)("|\'|="|=\')(.*)/i', "$3", $media[0]);

			foreach ($data as $url) {
				$info = pathinfo($url);
				if (isset($info['extension'])) {
					if ($info['filename'] == "spoiler-plus" or $info['filename'] == "spoiler-minus" or strpos($info['dirname'], 'engine/data/emoticons') !== false) continue;
					$info['extension'] = strtolower($info['extension']);
					if (($info['extension'] == 'jpg') || ($info['extension'] == 'jpeg') || ($info['extension'] == 'gif') || ($info['extension'] == 'png')) array_push($images, $url);
				}
			}

			if (count($images)) {
				$i = 0;
				foreach ($images as $url) {
					$i++;
					$tpl->copy_template = str_replace('{image-' . $i . '}', $url, $tpl->copy_template);
					$tpl->copy_template = str_replace('[image-' . $i . ']', "", $tpl->copy_template);
					$tpl->copy_template = str_replace('[/image-' . $i . ']', "", $tpl->copy_template);
				}

			}

			$tpl->copy_template = preg_replace("#\[image-(.+?)\](.+?)\[/image-(.+?)\]#is", "", $tpl->copy_template);
			$tpl->copy_template = preg_replace("#\\{image-(.+?)\\}#i", "{THEME}/dleimages/no_image.jpg", $tpl->copy_template);

		}


		if (stripos($tpl->copy_template, "{fullimage-") !== false) {

			$images = array();
			preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $news['full_story'], $media);
			$data = preg_replace('/(img|src)("|\'|="|=\')(.*)/i', "$3", $media[0]);

			foreach ($data as $url) {
				$info = pathinfo($url);
				if (isset($info['extension'])) {
					if ($info['filename'] == "spoiler-plus" or $info['filename'] == "spoiler-minus" or strpos($info['dirname'], 'engine/data/emoticons') !== false) continue;
					$info['extension'] = strtolower($info['extension']);
					if (($info['extension'] == 'jpg') || ($info['extension'] == 'jpeg') || ($info['extension'] == 'gif') || ($info['extension'] == 'png')) array_push($images, $url);
				}
			}

			if (count($images)) {
				$i = 0;
				foreach ($images as $url) {
					$i++;
					$tpl->copy_template = str_replace('{fullimage-' . $i . '}', $url, $tpl->copy_template);
					$tpl->copy_template = str_replace('[fullimage-' . $i . ']', "", $tpl->copy_template);
					$tpl->copy_template = str_replace('[/fullimage-' . $i . ']', "", $tpl->copy_template);
				}

			}

			$tpl->copy_template = preg_replace("#\[fullimage-(.+?)\](.+?)\[/fullimage-(.+?)\]#is", "", $tpl->copy_template);
			$tpl->copy_template = preg_replace("#\\{fullimage-(.+?)\\}#i", "{THEME}/dleimages/no_image.jpg", $tpl->copy_template);

		} // end-images

		$category_id = $news['category'];

		if (strpos($tpl->copy_template, "[catlist=") !== false) {
			$tpl->copy_template = preg_replace_callback("#\\[(catlist)=(.+?)\\](.*?)\\[/catlist\\]#is", "check_category", $tpl->copy_template);
		}

		if (strpos($tpl->copy_template, "[not-catlist=") !== false) {
			$tpl->copy_template = preg_replace_callback("#\\[(not-catlist)=(.+?)\\](.*?)\\[/not-catlist\\]#is", "check_category", $tpl->copy_template);
		}

		$parse = new ParseFilter();

		$count_start = substr_count ($tpl->copy_template, "[spoiler");
		$count_end = substr_count ($tpl->copy_template, "[/spoiler]");
		if ($count_start AND $count_start == $count_end) {
			$tpl->copy_template = str_ireplace( "[spoiler=]", "[spoiler]", $tpl->copy_template );
			while( preg_match( "#\[spoiler\](.+?)\[/spoiler\]#is", $tpl->copy_template ) ) {
				$tpl->copy_template = preg_replace_callback( "#\[spoiler\](.+?)\[/spoiler\]#is", array( &$parse, 'build_spoiler' ), $tpl->copy_template );
			}
			while( preg_match( "#\[spoiler=([^\]|\[|<]+)\](.+?)\[/spoiler\]#is", $tpl->copy_template ) ) {
				$tpl->copy_template = preg_replace_callback( "#\[spoiler=([^\]|\[|<]+)\](.+?)\[/spoiler\]#is", array( &$parse, 'build_spoiler' ), $tpl->copy_template);
			}
		}

		$tpl->copy_template = preg_replace_callback( "#\[(url)\](\S.+?)\[/url\]#i", array( &$parse, 'build_url'), $tpl->copy_template );
		$tpl->copy_template = preg_replace_callback( "#\[(url)\s*=\s*\&quot\;\s*(\S+?)\s*\&quot\;\s*\](.*?)\[\/url\]#i", array( &$parse, 'build_url'), $tpl->copy_template );
		$tpl->copy_template = preg_replace_callback( "#\[(url)\s*=\s*(\S.+?)\s*\](.*?)\[\/url\]#i", array( &$parse, 'build_url'), $tpl->copy_template );

		$tpl->copy_template = preg_replace_callback( "#\[(leech)\](\S.+?)\[/leech\]#i", array( &$parse, 'build_url'), $tpl->copy_template );
		$tpl->copy_template = preg_replace_callback( "#\[(leech)\s*=\s*\&quot\;\s*(\S+?)\s*\&quot\;\s*\](.*?)\[\/leech\]#i", array( &$parse, 'build_url'), $tpl->copy_template );
		$tpl->copy_template = preg_replace_callback( "#\[(leech)\s*=\s*(\S.+?)\s*\](.*?)\[\/leech\]#i", array( &$parse, 'build_url'), $tpl->copy_template );

		if (!$lpset['external_page']) {
			$tpl->compile('content');
		} else {

			$tpl->compile('download');
			echo $tpl->result['download'];

			die();
		}

	} else {
		msgbox($lang['mwslp_3'], implode("<br />", $err_module));

	}

} else {
	msgbox($lang['mwslp_3'], $lang['mwslp_2']);
}

