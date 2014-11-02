<?php
/*
 *  AnyExample's good_query_... library. 
 *  
 *  See:
 *  http://www.anyexample.com/programming/php/5_useful_php_functions_for_mysql_data_fetching.xml
 *
 *  Published under AnyExample License:
 *  http://www.anyexample.com/license.xml
 *   
 *   - Do whatever you want, but do not publish 
 *     in an article or book.
 *     
 *   - Code provided as is, without any warranty
 */

function good_query($string, $debug=0)
{
    global $db;
    if ($debug == 1)
        print $string;

    if ($debug == 2)
        error_log($string);

    $result = $db->query($string);

    if ($result == false)
    {
        error_log("SQL error: ".$db->error."\n\nOriginal query: $string\n");
    }
    return $result;
}

function good_query_list($sql, $debug=0)
{
    // this function require presence of good_query() function
    $result = good_query($sql, $debug);
	
    if($lst = $result->fetch_row())
    {
    $result->free();
	return $lst;
    }
    $result->free();
    return false;
}

function good_query_assoc($sql, $debug=0)
{
    // this function require presence of good_query() function
    $result = good_query($sql, $debug);
	
    if($lst = $result->fetch_assoc())
    {
	$result->free();
	return $lst;
    }
    $result->free();
    return false;
}

function good_query_value($sql, $debug=0)
{
    // this function require presence of good_query_list() function
    $lst = good_query_list($sql, $debug);
    return is_array($lst)?$lst[0]:false;
}

function good_query_table($sql, $debug=0)
{
    // this function require presence of good_query() function
    $result = good_query($sql, $debug);
	
    $table = array();
    if ($result->num_rows > 0)
    {
        $i = 0;
        while($table[$i] = $result->fetch_assoc()) 
			$i++;
        unset($table[$i]);                                                                                  
    }                                                                                                                                     
    $result->free();
    return $table;
}


?>