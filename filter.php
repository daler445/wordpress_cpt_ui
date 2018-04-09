<?php
  // в файле шаблоне
  // in template file
?>

<?php
    // функция получения "типа документов" по айди
    function getTermNameById($g_id) {
        if ($g_id == null) {
            return;
        }
        else {
            $document_types_terms = get_terms(
                'document_types', 
                    array(
                        'hide_empty' => false,
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'number'     => 1,
                        'include'    => $g_id
                    )
            );
            //print_r($document_types_terms);
            return $document_types_terms[0]->name;
        }
    }

    // функция для получения имени "издателя" по айди
    function getIzdatelById($g_id) {
    	if ($g_id == null) {
    		return;
    	}
    	else {
    		$document_izdateli = get_terms(
                'document_izdateli', 
                    array(
                        'hide_empty' => false,
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'number'     => 1,
                        'include'    => $g_id
                    )
            );
            return $document_izdateli[0]->name;
    	}
    }
    
    // функция для получения данных от $_POST и $_GET
    function getFilterData($str) {
        if ($str != null) {
            if ($_POST[$str] != null) {
                $res = $_POST[$str];
                return $res;
            }
            else {
                if ($_GET[$str] != null) {
                    $res = $_GET[$str];
                    return $res;
                }
                else {
                    return false;
                }
            }
        }
        else {
            return false;
        }
    }

    // переменные фильтра
    $search_val = null;
    $document_type_val = null;
    $pubdate = null;
    $date_izd = null;
    $dc_izdateli = null;

    $search_val = getFilterData("str");
    $document_type_val = getFilterData("dc_type");
    $pubdate = getFilterData("pubdate");
    $date_izd = getFilterData("date_izd");
    $dc_izdateli = getFilterData("dc_izdateli");

    // новый формат даты - Ymd
    if ($date_izd != null) {    	
    	$newDate = DateTime::createFromFormat('d-m-Y', $date_izd);

    	$izd_date = $newDate->format('Ymd');
    	$izd_year = $newDate->format('Y');
    	$izd_month = $newDate->format('m');
    	$izd_day = $newDate->format('d');
    }
?>
<?php
  // форма фильтра 
?>
                        <form method="POST" action="" >
                            <span>Документы опубликованные за:<br /></span>
                            <select name="pubdate">
                                <option <?php if ($pubdate == null) { echo "selected"; } ?>>
                                    Всё время
                                </option>
                                <option value="today" <?php if ($pubdate == "today") { echo "selected"; } ?> >
                                    Сегодня
                                </option>
                                <option value="week" <?php if ($pubdate == "week") { echo "selected"; } ?> >
                                    Последнюю неделю
                                </option>
                                <option value="month" <?php if ($pubdate == "month") { echo "selected"; } ?> >
                                    Месяц
                                </option>
                            </select>
                            <br /><br />
                            <span>Поисковая фраза<br /></span>
                            <input type="text" name="str" value="<?php if ($search_val) { echo $search_val; } ?>">
                            <br /><br />
                            <span>Вид документа<br /></span>
                            <select name="dc_type">
                                <option></option>
                                <?php
                                        $document_types_terms = get_terms(
                                            'document_types', 
                                            array(
                                                'hide_empty' => false,
                                                'orderby'    => 'name',
                                                'order'      => 'ASC'
                                            )
                                        );
                                        foreach( $document_types_terms as $document_type ):
                                            $selected = null;
                                            if ($document_type_val != null) {
                                                if ($document_type_val == $document_type->term_id) {
                                                    $selected = "selected";
                                                }
                                            }
                                    ?>
                                    <option value="<?php echo $document_type->term_id; ?>" <?php echo $selected; ?>>
                                        <?php echo $document_type->name; ?>
                                    </option>
                                    <?php endforeach; ?>
                            </select>
                            <br /><br />
                            <span>По подразделениям МФ</span><br />
                            <select>
                                <option selected="selected"></option>
                                <option>---------------</option>
                                <option>---------------</option>
                            </select>
                            <br /><br />
                            <span>Издатель документа</span><br />
                            <select name="dc_izdateli">
                                <option selected="selected"></option>
                                <?php
                                        $document_izd_s = get_terms(
                                            'document_izdateli', 
                                            array(
                                                'hide_empty' => false,
                                                'orderby'    => 'name',
                                                'order'      => 'ASC'
                                            )
                                        );
                                        foreach( $document_izd_s as $document_izd ):
                                            $selected = null;
                                            if ($dc_izdateli != null) {
                                                if ($dc_izdateli == $document_izd->term_id) {
                                                    $selected = "selected";
                                                }
                                            }
                                    ?>
                                    <option value="<?php echo $document_izd->term_id; ?>" <?php echo $selected; ?>>
                                        <?php echo $document_izd->name; ?>
                                    </option>
                                    <?php endforeach; ?>
                            </select>
                            <br /><br />
                            <span>Дата издания</span><br />
                            <input type="text" id="filter_datepicker" name="date_izd" value="<?php if ($date_izd) { echo $date_izd; } ?>">
                            <br /><br />
                            <input type="submit" value="action">
                        </form>
<?php
  // показывает параметры поиска
?>
                        <p>
                            Поисковая фраза: <?php echo $search_val; ?>
                            <br /><br />
                            Вид документа: <?php echo getTermNameById($document_type_val); ?>
                            <br /><br />
                            Издатель документа: <?php echo getIzdatelById($dc_izdateli); ?>
                            <br /><br />
                            Дата публикации: <?php echo $pubdate; ?>
                            <br /><br />
                            Дата издания: <?php echo $date_izd; ?>
                        </p>
<?php
  // запросы по параметрам
                    $current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $per_page = '10';

                    if (($search_val != null) || ($document_type_val != null) || ($pubdate != null) || ($dc_izdateli != null) || ($date_izd != null)) {

                        // запрос на все записи - без параметров
                        $search_query = 
                        	array(
                                'post_type' => 'documents',
                                'posts_per_page' => $per_page,
                                'paged' => $current_page,
                                's' => ''
                            );

                        // фильтр по фразе
                        if ($search_val != null) {
                            $c_arr = array('s'=>$search_val);
                            $search_query = $search_query + $c_arr;
                        }
                        
                        // фильтр по taxonomy (несколько)
			if  (($document_type_val != null) || ($dc_izdateli != null)) {
                            $c_arr = null;

                            $c_1 = null;
                            $c_2 = null;

                            if ($document_type_val != null) {
                                $c_1 = 
                                    array(
                                        'taxonomy'      =>      'document_types',
                                        'field'         =>      'term_id',
                                        'terms'         =>      $document_type_val
                                    );
                            }

                            if ($dc_izdateli != null) {
                                $c_2 =
                                    array(
                                        'taxonomy'      =>      'document_izdateli',
                                        'field'         =>      'term_id',
                                        'terms'         =>      $dc_izdateli
                                    );
                            }

                            $c_arr = array(
                                array(
                                    'relation'          =>      'AND',
                                    $c_1,
                                    $c_2
                                )
                            );
                            $c_pre = 
                                array(
                                    'tax_query'         =>      $c_arr
                                );

                            $search_query = $search_query + $c_pre;
                        }

                        // фильтр по дате публикации - custom field
                        if ($pubdate != null) {
                        	$today = date( 'Ymd' );
                        	$week_ago = date( 'Ymd', strtotime('-7 days') );
                        	$month_ago = date( 'Ymd', strtotime('-30 days') );
                        	//echo "<script>alert('".$today."');</script>";
                        	//echo $today;
                        	if ($pubdate == "today") {
                        		$c_arr = array(
                        			'meta_query' => array(
                        				array(
							              'key' => 'document_site_date',
							              'value' => $today,
							              'compare' => '=',
							            )
                        			)
                        		);
                        		$search_query = $search_query + $c_arr;
                        	}
                        	if ($pubdate == "week") {
								$c_arr = array(
                        			'meta_query' => array(
                        				'relation' => 'AND',
                        				array(
							              	'key' => 'document_site_date',
							              	'value' => $today,
							              	'compare' => '<=',
							            ),
                        				array(
                        					'key' => 'document_site_date',
							              	'value' => $week_ago,
							              	'compare' => '>=',
                        				)
                        			)
                        		);
                        		$search_query = $search_query + $c_arr;
                        	}
                        	if ($pubdate == "month") {
								$c_arr = array(
                        			'meta_query' => array(
                        				'relation' => 'AND',
                        				array(
							              	'key' => 'document_site_date',
							              	'value' => $today,
							              	'compare' => '<=',
							            ),
                        				array(
                        					'key' => 'document_site_date',
							              	'value' => $month_ago,
							              	'compare' => '>=',
                        				)
                        			)
                        		);
                        		$search_query = $search_query + $c_arr;
                        	}
                        } // else - for all time

                        // фильтр по дате издания - custom field
                        if ($date_izd != null) {
                        	//echo "<script>alert('".$izd_date."');</script>";
                        	$c_arr = array(
                        		'meta_query' => array(
                        			array(
							            'key' => 'document_date_creation',
							            'value' => $izd_date
							        )
                        		)
                        	);
                        	$search_query = $search_query + $c_arr;
                        }

                        $documents = new WP_Query($search_query);
                    }
  // и без параметра
                    else {
                        $documents = new WP_Query(array(
                            'post_type' => 'documents',
                            'posts_per_page' => $per_page,
            				'paged' => $current_page
                        ));
                    }
                    $post_count = $documents->found_posts;
?>

<?php
  // отображение записи
?>

<?php while($documents->have_posts()) : $documents->the_post(); ?>
  <?php the_title(); ?>
<?php wp_reset_postdata(); ?>
<?php endwhile; ?>



<?php
  // пагинация
?>
<?php    
                    	$total_pages = (int)($post_count / $per_page);
                    	$ostatok = $post_count % $per_page;
                    	
                      if ($ostatok > 0) {
                    		$total_pages = $total_pages + 1;
                    	}
                      
                    	echo "<br />";
                    	echo "current page: " . $paged;
                    	echo "<br />";
                    	echo "total pages: " . $total_pages;
                    	echo "<br />";
                    	echo "post count: " . $post_count;
                    	echo "<br />";

                      // добавление параметров для пагинации в переменную
                    	$addtolink = null;
                    	if ($pubdate != null) {
                    		$addtolink .= "&pubdate=".$pubdate;
                    	}
                    	if ($search_val != null) {
                    		$addtolink .= "&str=".$search_val;
                    	}
                    	if ($document_type_val != null) {
                    		$addtolink .= "&dc_type=".$document_type_val;
                    	}
                    	if ($dc_izdateli != null) {
                    		$addtolink .= "&dc_izdateli=".$dc_izdateli;
                    	}
                    	if ($date_izd != null) {
                    		$addtolink .= "&date_izd=".$date_izd;
                    	}

                      // ссылки
                    	if (($total_pages > 1) && ($paged > 1)) {
                    		$raw_url = get_previous_posts_page_link();
                            $raw_url .= $addtolink;
                    		echo "<a href='".$raw_url."'>prev</a> ";
                    	}
                    	if (($paged == $total_pages) && ($paged == 1)) {
                    		// first and only page
                    	}
                    	if ($paged < $total_pages) {
                    		$raw_url = get_next_posts_page_link();	
                    		$raw_url .= $addtolink;
                    		echo "<a href='".$raw_url."'>next</a> ";
                    	}
?>
