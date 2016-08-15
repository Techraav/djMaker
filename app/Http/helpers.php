<?php
	function userData($wanted, $value, $field='id')
	{
		$user = App\User::where($field, $value)->first();
		$data = $user[$wanted];

		return $data;
	}

	function cut($str, $n, $link=false)
	{
		$string = $str;
		if(strlen($str) > $n){
			$substr = substr($str, 0, $n);
			$left = '<i>[...]</i>';
			if($link != false)
			{
				$left = '<a title="Cliquez pour voir la suite" href="'.url($link).'">'.$left.'</a>';
			}
			$string = $substr.$left.'</p>';
		}
		return $string;
	}

	function glyph($str)
	{
		return 'glyphicon glyphicon-'.$str;
	}

	function getWeekDay($date = "")
	{
		if($date == "")
		{
			$date = strtotime(date("Y-m-d"));
		}
		$day = date('N', $date)-1;
		return day($day);
	}

	function printDay($id, $three=false) 
	{
		return day($id, $three);
	}

	/**
	*	Store a modification
	*
	* @param $table : string
	* @param $msg : string
	* @param $author : user_id, default = Auth::user()->id
	*
	* @return response
	*/
	function makeModification($table, $msg, $author=false)
	{
		return App\Modification::create([
			'table'		=> $table,
			'user_id'	=> $author == false ? Auth::user()->id : $author,
			'message'	=> $msg
			]);
	}

	/**
	* return day in French
	* 
	* @param $n (int) : day 0-7
	*
	* @return $day string
	*/
	function day($n, $three=false)
	{
		$days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
		$day = $days[$n];

		if($three)
		{
			$day = substr($day, 0, 3);
		}

		return $day;
	}

	function printLink($url, $msg, array $attributes=[], array $classes=[])
	{
		$class = '';
		if(!empty($classes))
		{
			$class .= 'class="';
			foreach ($classes as $c) {
				$class .= $c.' ';
			}
			$class .= '"';
		}
		$link = '<a ';

		if(!empty($attributes))
		{
			foreach($attributes as $k => $v)
				$link .= $k.'="'.$v.'" ';
		}

		$link .= $class.' href="'.url($url).'">'. $msg .'</a>';

		return $link;
	}

	function printUserLink(App\User $user, $firstThenLast = true, array $classes=[])
	{
		$class = '';
		if(!empty($classes))
		{
			$class .= 'class="';
			foreach ($classes as $c) {
				$class .= $c.' ';
			}
			$class .= '"';
		}

		$name = $user->name;

		$link = '<a '.$class.' href="'.url('users/'.$user->id).'">'. ucfirst($name) .'</a>';

		return $link;
	}

	function printEventLink(App\Event $event, array $classes=[], array $arrayAttributes=[])
	{
		$class = '';
		if(!empty($classes))
		{
			$class .= 'class="';
			foreach ($classes as $c) {
				$class .= $c.' ';
			}
			$class .= '"';
		}

		$attributes = '';
		if(!empty($arrayAttributes))
		{
			foreach ($arrayAttributes as $k => $v) {
				$attributes .= $k.'="'.$v.'" ';
			}
		}

		$name = $event->name;

		$link = '<a '.$class.' '.$attributes.' href="'.url('events/show/'.$event->id).'">'. ucfirst($name) .'</a>';

		return $link;
	}

	function showDate($date, $fromFormat, $format, $suff=true)
	{	
		$dateTime = date_create_from_format($fromFormat, $date);
		if(is_bool($dateTime))
		{
			$dateTime = date_create_from_format('Y-m-d', '1900-01-01');
		}

		$date = date_format($dateTime, $format);

		if(strpos($format, 'D') > -1)
		{
			$date = str_replace('Mon', 'Lun', $date);
			$date = str_replace('Tue', 'Mar', $date);
			$date = str_replace('Wed', 'Mer', $date);
			$date = str_replace('Thu', 'Jeu', $date);
			$date = str_replace('Fri', 'Ven', $date);
			$date = str_replace('Sat', 'Sam', $date);
			$date = str_replace('Sun', 'Dim', $date);
		}
		if(strpos($format, 'M') > -1)
		{
			$date = str_replace('Feb', 'Fév', $date);
			$date = str_replace('Apr', 'Avr', $date);
			$date = str_replace('May', 'Mai', $date);
			$date = str_replace('Jun', 'Juin', $date);
			$date = str_replace('Jul', 'Juil', $date);
			$date = str_replace('Aug', 'Aoû', $date);
		}
		if(strpos($format, 'F') > -1)
		{
			$date = str_replace('January', 'Janvier', $date);
			$date = str_replace('February', 'Février', $date);
			$date = str_replace('March', 'Mars', $date);
			$date = str_replace('April', 'Avril', $date);
			$date = str_replace('May', 'Mai', $date);
			$date = str_replace('June', 'Juin', $date);
			$date = str_replace('July', 'Juillet', $date);
			$date = str_replace('August', 'Août', $date);
			$date = str_replace('September', 'Septembre', $date);
			$date = str_replace('October', 'Octobre', $date);
			$date = str_replace('November', 'Novembre', $date);
			$date = str_replace('December', 'Décembre', $date);
		}
		if($suff && strpos($format, 'j') > -1)
		{
			$date = str_replace('1st', '1er', $date);
			$date = str_replace('2nd', '2ème', $date);
			$date = str_replace('3rd', '3ème', $date);
			$date = str_replace('th ', 'ème ', $date);
		}
		if(strpos($format, 'l') > -1)
		{
			$date = str_replace('Monday', 'Lundi', $date);
			$date = str_replace('Tuesday', 'Mardi', $date);
			$date = str_replace('Wednesday', 'Mercredi', $date);
			$date = str_replace('Thursday', 'Jeudi', $date);
			$date = str_replace('Friday', 'Vendredi', $date);
			$date = str_replace('Saturday', 'Samedi', $date);
			$date = str_replace('Sunday', 'Dimanche', $date);
		}		

		return $date;
	}

	function createWithSlug($class, array $data=[])
    {
    	$model = $class::create($data);
    	$nameField = $class::NAMEFIELD;

    	$name = normalizeChars($model[$nameField]);

        $stringToSlug = $name.'-'.$model->id;
        
        $slug = str_slug($stringToSlug);
        
        $model->update([
            'slug'  => $slug,
            ]);

        return $model;

    }

	function normalizeChars($s) 
	{
	    $replace = array(
	        'ъ'=>'-', 'Ь'=>'-', 'Ъ'=>'-', 'ь'=>'-',
	        'Ă'=>'A', 'Ą'=>'A', 'À'=>'A', 'Ã'=>'A', 'Á'=>'A', 'Æ'=>'A', 'Â'=>'A', 'Å'=>'A', 'Ä'=>'Ae',
	        'Þ'=>'B',
	        'Ć'=>'C', 'ץ'=>'C', 'Ç'=>'C',
	        'È'=>'E', 'Ę'=>'E', 'É'=>'E', 'Ë'=>'E', 'Ê'=>'E',
	        'Ğ'=>'G',
	        'İ'=>'I', 'Ï'=>'I', 'Î'=>'I', 'Í'=>'I', 'Ì'=>'I',
	        'Ł'=>'L',
	        'Ñ'=>'N', 'Ń'=>'N',
	        'Ø'=>'O', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe',
	        'Ş'=>'S', 'Ś'=>'S', 'Ș'=>'S', 'Š'=>'S',
	        'Ț'=>'T',
	        'Ù'=>'U', 'Û'=>'U', 'Ú'=>'U', 'Ü'=>'Ue',
	        'Ý'=>'Y',
	        'Ź'=>'Z', 'Ž'=>'Z', 'Ż'=>'Z',
	        'â'=>'a', 'ǎ'=>'a', 'ą'=>'a', 'á'=>'a', 'ă'=>'a', 'ã'=>'a', 'Ǎ'=>'a', 'а'=>'a', 'А'=>'a', 'å'=>'a', 'à'=>'a', 'א'=>'a', 'Ǻ'=>'a', 'Ā'=>'a', 'ǻ'=>'a', 'ā'=>'a', 'ä'=>'ae', 'æ'=>'ae', 'Ǽ'=>'ae', 'ǽ'=>'ae',
	        'б'=>'b', 'ב'=>'b', 'Б'=>'b', 'þ'=>'b',
	        'ĉ'=>'c', 'Ĉ'=>'c', 'Ċ'=>'c', 'ć'=>'c', 'ç'=>'c', 'ц'=>'c', 'צ'=>'c', 'ċ'=>'c', 'Ц'=>'c', 'Č'=>'c', 'č'=>'c', 'Ч'=>'ch', 'ч'=>'ch',
	        'ד'=>'d', 'ď'=>'d', 'Đ'=>'d', 'Ď'=>'d', 'đ'=>'d', 'д'=>'d', 'Д'=>'D', 'ð'=>'d',
	        'є'=>'e', 'ע'=>'e', 'е'=>'e', 'Е'=>'e', 'Ə'=>'e', 'ę'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'Ē'=>'e', 'Ė'=>'e', 'ė'=>'e', 'ě'=>'e', 'Ě'=>'e', 'Є'=>'e', 'Ĕ'=>'e', 'ê'=>'e', 'ə'=>'e', 'è'=>'e', 'ë'=>'e', 'é'=>'e',
	        'ф'=>'f', 'ƒ'=>'f', 'Ф'=>'f',
	        'ġ'=>'g', 'Ģ'=>'g', 'Ġ'=>'g', 'Ĝ'=>'g', 'Г'=>'g', 'г'=>'g', 'ĝ'=>'g', 'ğ'=>'g', 'ג'=>'g', 'Ґ'=>'g', 'ґ'=>'g', 'ģ'=>'g',
	        'ח'=>'h', 'ħ'=>'h', 'Х'=>'h', 'Ħ'=>'h', 'Ĥ'=>'h', 'ĥ'=>'h', 'х'=>'h', 'ה'=>'h',
	        'î'=>'i', 'ï'=>'i', 'í'=>'i', 'ì'=>'i', 'į'=>'i', 'ĭ'=>'i', 'ı'=>'i', 'Ĭ'=>'i', 'И'=>'i', 'ĩ'=>'i', 'ǐ'=>'i', 'Ĩ'=>'i', 'Ǐ'=>'i', 'и'=>'i', 'Į'=>'i', 'י'=>'i', 'Ї'=>'i', 'Ī'=>'i', 'І'=>'i', 'ї'=>'i', 'і'=>'i', 'ī'=>'i', 'ĳ'=>'ij', 'Ĳ'=>'ij',
	        'й'=>'j', 'Й'=>'j', 'Ĵ'=>'j', 'ĵ'=>'j', 'я'=>'ja', 'Я'=>'ja', 'Э'=>'je', 'э'=>'je', 'ё'=>'jo', 'Ё'=>'jo', 'ю'=>'ju', 'Ю'=>'ju',
	        'ĸ'=>'k', 'כ'=>'k', 'Ķ'=>'k', 'К'=>'k', 'к'=>'k', 'ķ'=>'k', 'ך'=>'k',
	        'Ŀ'=>'l', 'ŀ'=>'l', 'Л'=>'l', 'ł'=>'l', 'ļ'=>'l', 'ĺ'=>'l', 'Ĺ'=>'l', 'Ļ'=>'l', 'л'=>'l', 'Ľ'=>'l', 'ľ'=>'l', 'ל'=>'l',
	        'מ'=>'m', 'М'=>'m', 'ם'=>'m', 'м'=>'m',
	        'ñ'=>'n', 'н'=>'n', 'Ņ'=>'n', 'ן'=>'n', 'ŋ'=>'n', 'נ'=>'n', 'Н'=>'n', 'ń'=>'n', 'Ŋ'=>'n', 'ņ'=>'n', 'ŉ'=>'n', 'Ň'=>'n', 'ň'=>'n',
	        'о'=>'o', 'О'=>'o', 'ő'=>'o', 'õ'=>'o', 'ô'=>'o', 'Ő'=>'o', 'ŏ'=>'o', 'Ŏ'=>'o', 'Ō'=>'o', 'ō'=>'o', 'ø'=>'o', 'ǿ'=>'o', 'ǒ'=>'o', 'ò'=>'o', 'Ǿ'=>'o', 'Ǒ'=>'o', 'ơ'=>'o', 'ó'=>'o', 'Ơ'=>'o', 'œ'=>'oe', 'Œ'=>'oe', 'ö'=>'oe',
	        'פ'=>'p', 'ף'=>'p', 'п'=>'p', 'П'=>'p',
	        'ק'=>'q',
	        'ŕ'=>'r', 'ř'=>'r', 'Ř'=>'r', 'ŗ'=>'r', 'Ŗ'=>'r', 'ר'=>'r', 'Ŕ'=>'r', 'Р'=>'r', 'р'=>'r',
	        'ș'=>'s', 'с'=>'s', 'Ŝ'=>'s', 'š'=>'s', 'ś'=>'s', 'ס'=>'s', 'ş'=>'s', 'С'=>'s', 'ŝ'=>'s', 'Щ'=>'sch', 'щ'=>'sch', 'ш'=>'sh', 'Ш'=>'sh', 'ß'=>'ss',
	        'т'=>'t', 'ט'=>'t', 'ŧ'=>'t', 'ת'=>'t', 'ť'=>'t', 'ţ'=>'t', 'Ţ'=>'t', 'Т'=>'t', 'ț'=>'t', 'Ŧ'=>'t', 'Ť'=>'t', '™'=>'tm',
	        'ū'=>'u', 'у'=>'u', 'Ũ'=>'u', 'ũ'=>'u', 'Ư'=>'u', 'ư'=>'u', 'Ū'=>'u', 'Ǔ'=>'u', 'ų'=>'u', 'Ų'=>'u', 'ŭ'=>'u', 'Ŭ'=>'u', 'Ů'=>'u', 'ů'=>'u', 'ű'=>'u', 'Ű'=>'u', 'Ǖ'=>'u', 'ǔ'=>'u', 'Ǜ'=>'u', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'У'=>'u', 'ǚ'=>'u', 'ǜ'=>'u', 'Ǚ'=>'u', 'Ǘ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ü'=>'ue',
	        'в'=>'v', 'ו'=>'v', 'В'=>'v',
	        'ש'=>'w', 'ŵ'=>'w', 'Ŵ'=>'w',
	        'ы'=>'y', 'ŷ'=>'y', 'ý'=>'y', 'ÿ'=>'y', 'Ÿ'=>'y', 'Ŷ'=>'y',
	        'Ы'=>'y', 'ž'=>'z', 'З'=>'z', 'з'=>'z', 'ź'=>'z', 'ז'=>'z', 'ż'=>'z', 'ſ'=>'z', 'Ж'=>'zh', 'ж'=>'zh'
	    	);

	    return strtr($s, $replace);
	}

	function str_search($search)
	{
		$search = trim($search);
		$accents = ['é','è','ç','à','ù','â','ê','û','î','ô','ä','ë','ü','ï','ö','Â','Ê','Î','Ô','Û','Ä','Ë','Ï','Ö','Ü','À','Æ','æ','Ç','É','È','Œ','œ','Ù'];
		$str = str_replace($accents, '_', $search);

		return $str;
	}

	function createEvents()
    {
    	$c = 0;
        for ($i=1; $i < 13; $i++) { 
            for($j=1; $j<29; $j++)
            {
                $n = rand(0,2);
                if($n === 1)
                {
                    App\Event::create([
                        'name'  => 'test event '.$c++,
                        'user_id'   => 2,
                        'slug'  => 'test-auto-event-'.$c,
                        'date'  => '2016-'.($i < 10 ? '0'.$i : $i).'-'.($j < 10 ? '0'.$j : $j),
                        'start' => '19:00:00',
                        'end'   => '5:00:00',
                        ]);
                }
            }
        }
    }

    function isOwner($model)
    {
    	return Auth::check() && $model->user_id == Auth::user()->id;
    }

    function printDate($str, $fromFormat, $toFormat='d/m/Y')
    {
    	return DateTime::createFromFormat($fromFormat, $str)->format($toFormat);
    }	

    // function setDBDates()
    // {
    // 	$articles = App\Article::all();
    // 	$comments = App\Comment::all();
    // 	$events = App\Event::all();
    // 	$eventps = App\EventPlaylist::all();
    // 	$likes = App\Likes::all();
    // 	$news = App\News::all();
    // 	$playlists = App\Playlist::all();
    // 	$playlistvs = App\PlaylistVideo::all();
    // 	$users = App\User::all();
    // 	$videos = App\Video::all();

    // 	$array = [$articles, $comments, $events, $eventps, $likes, $news, $playlists, $playlistvs, $users, $videos];

    // 	return $array[0][0];

    // 	foreach ($array as $model) {
    // 		foreach ($model as $m) {
    // 			$model->update([
    // 				'created_at' => '2016-05-21',
    // 				'updated_at' => '2016-05-21',
    // 				]);
    // 		}
    // 	}
    // }

    // function getGoogleClient()
    // {
    // 	$client = new Google_Client();
    // 	dd($client);
    // }

    function loadVideoInfo(&$video)
    {
    	$isArray = is_array($video);
    	if($isArray)
    	{
    		foreach ($video as $v) {
				$infos = Youtube::getVideoInfo($v->url);
				$v->infos = $infos;
    		}
    	}else
    	{
    		$infos = Youtube::getVideoInfo($video->url);
    		$video->infos = $infos;
    	}
    }

    function likesAndDislikes($data)	
    {
    	$array = ['likes' => 0, 'dislikes' => 0];
    	foreach($data as $d)
    	{
    		if($d->value == 1)
    		{
    			$array['likes']++;
    		}elseif($d->value == -1)
    		{
    			$array['dislikes']++;
    		}
    	}

    	return $array;
    }

?>