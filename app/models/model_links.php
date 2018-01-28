<?php

defined('LETTER') || exit('NewsLetter: access denied.');


class Model_links extends Model
{
    /**
     * @param $strtmp
     * @param $search
     * @param $category
     * @param $page
     * @param $pnumber
     * @return mixed
     */
    public function getLinksArr($strtmp, $search, $page, $pnumber)
    { 
        core::database()->tablename = core::database()->getTableName('links') . " fb LEFT JOIN " . core::database()->getTableName('users') . " usr ON fb.user_id=usr.id_user";
        if ($search) {
            $_search = core::database()->escape($search);
            
            $temp = strtok($_search, " ");
            $temp = "%" . $temp . "%";
            $logstr = "or";
            $tmpl = null;
            $is_query = null;

            while ($temp) {
                if ($is_query)
                    $tmpl .= " $logstr (name LIKE '" . $temp . "' OR email LIKE '" . $temp . "' OR url LIKE '" . $temp . "' OR fb.country LIKE '" . $temp . "' OR fb.city LIKE '" . $temp . "') ";
                else
                    $tmpl .= "(name LIKE '" . $temp . "' OR email LIKE '" . $temp . "' OR url LIKE '" . $temp . "' OR fb.country LIKE '" . $temp . "' OR fb.city LIKE '" . $temp . "') ";
                
                $is_query = true;
                $temp = strtok(" ");
            }
            
            core::database()->parameters = "*, fb.ip AS ip_links, fb.country AS country_links, fb.city AS city_links";
            core::database()->where = "WHERE " . $tmpl . "";
            core::database()->order = "ORDER BY id";
        } else {
            core::database()->tablename = "" . core::database()->getTableName('links') . " fb LEFT JOIN " . core::database()->getTableName('users') . " usr ON fb.user_id=usr.id_user";
            core::database()->parameters = "*, fb.ip AS ip_links, fb.country AS country_links, fb.city AS city_links";
            core::database()->order = "ORDER BY $strtmp";
        }
        
        core::database()->pnumber = $pnumber;
        core::database()->page = $page;
        
        return core::database()->get_page();
    }

    /**
     * @return mixed
     */
    public function countLinks()
    {
        if (Core_Array::getRequest('search')) {
            $_search = core::database()->escape(Core_Array::getRequest('search'));
            
            $temp = strtok($_search, " ");
            $temp = "%" . $temp . "%";
            $logstr = "or";
            $tmpl = null;
            $is_query = null;

            while ($temp) {
                if ($is_query)
                    $tmpl .= " $logstr (name LIKE '" . $temp . "' OR email LIKE '" . $temp . "' OR url LIKE '" . $temp . "' OR fb.country LIKE '" . $temp . "' OR fb.city LIKE '" . $temp . "') ";
                else
                    $tmpl .= "(name LIKE '" . $temp . "' OR email LIKE '" . $temp . "' OR url LIKE '" . $temp . "' OR fb.country LIKE '" . $temp . "' OR fb.city LIKE '" . $temp . "') ";
                
                $is_query = true;
                $temp = strtok(" ");
            }
            
            $query = "SELECT * FROM " . core::database()->getTableName('links') . " fb LEFT JOIN " . core::database()->getTableName('users') . " usr ON fb.user_id=usr.id_user WHERE " . $tmpl;
        } else {
            $query = "SELECT * FROM " . core::database()->getTableName('links') . " fb LEFT JOIN " . core::database()->getTableName('users') . " usr ON fb.user_id=usr.id_user ";
        }
        $result = core::database()->querySQL($query);
        return core::database()->getRecordCount($result);
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        core::database()->tablename = core::database()->getTableName('links') . " fb LEFT JOIN " . core::database()->getTableName('users') . " usr ON fb.user_id=usr.id_user";
        
        $number = intval((core::database()->get_total() - 1) / core::database()->pnumber) + 1;
        
        return $number;
    }

    /**
     * @return mixed
     */
    public function getPageNumber()
    {
        return core::database()->page;
    }
}
