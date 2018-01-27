<?php

defined('LETTER') || exit('NewsLetter: access denied.');


class Model_feedback extends Model
{
    /**
     * @param $strtmp
     * @param $search
     * @param $category
     * @param $page
     * @param $pnumber
     * @return mixed
     */
    public function getFeedbackArr($strtmp, $search, $page, $pnumber)
    { 
        core::database()->tablename = core::database()->getTableName('feedback') . " fb LEFT JOIN " . core::database()->getTableName('users') . " usr ON fb.user_id=usr.id_user";
        if ($search) {
            $_search = core::database()->escape($search);
            
            $temp = strtok($_search, " ");
            $temp = "%" . $temp . "%";
            $logstr = "or";
            $tmpl = null;
            $is_query = null;

            while ($temp) {
                if ($is_query)
                    $tmpl .= " $logstr (name LIKE '" . $temp . "' OR email LIKE '" . $temp . "' OR content LIKE '" . $temp . "') ";
                else
                    $tmpl .= "(name LIKE '" . $temp . "' OR email LIKE '" . $temp . "' OR content LIKE '" . $temp . "') ";
                
                $is_query = true;
                $temp = strtok(" ");
            }
            
            core::database()->parameters = "*,DATE_FORMAT(time,'%d.%m.%y') as putdate_format";
            core::database()->where = "WHERE " . $tmpl . "";
            core::database()->order = "ORDER BY id";
        } else {
            core::database()->tablename = "" . core::database()->getTableName('feedback') . " fb LEFT JOIN " . core::database()->getTableName('users') . " usr ON fb.user_id=usr.id_user";
            core::database()->parameters = "*,DATE_FORMAT(time,'%d.%m.%y') as putdate_format, usr.name AS name";
            core::database()->order = "ORDER BY $strtmp";
        }
        
        core::database()->pnumber = $pnumber;
        core::database()->page = $page;
        
        return core::database()->get_page();
    }

    /**
     * @return mixed
     */
    public function countFeedback()
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
                    $tmpl .= " $logstr (name LIKE '" . $temp . "' OR email LIKE '" . $temp . "') ";
                else
                    $tmpl .= "(name LIKE '" . $temp . "' OR email LIKE '" . $temp . "') ";
                
                $is_query = true;
                $temp = strtok(" ");
            }
            
            $query = "SELECT *,DATE_FORMAT(time,'%d.%m.%y') as putdate_format FROM " . core::database()->getTableName('feedback') . " fb LEFT JOIN " . core::database()->getTableName('users') . " usr ON fb.user_id=usr.id_user WHERE " . $tmpl;
        } else {
            $query = "SELECT *,DATE_FORMAT(time,'%d.%m.%y') as putdate_format FROM " . core::database()->getTableName('feedback') . " fb LEFT JOIN " . core::database()->getTableName('users') . " usr ON fb.user_id=usr.id_user ";
        }
        $result = core::database()->querySQL($query);
        return core::database()->getRecordCount($result);
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        core::database()->tablename = core::database()->getTableName('feedback') . " fb LEFT JOIN " . core::database()->getTableName('users') . " usr ON fb.user_id=usr.id_user";
        
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
    
    public function deleteFeedback($aId)
    {
        $return = true;
        foreach ($aId as $id){
            if ($return) {
                $return = $this->removeFeedback($id);
                if (!$return) {
                    break;
                }
            }
        }
        
        return $return;
    }
    
    /**
     * @param $id
     * @return bool
     */
    public function removeFeedback($id)
    {
        if (is_numeric($id)) {
            return core::database()->delete(core::database()->getTableName('feedback'), "id=" . $id);
        }
        
        return false;
    }
}
