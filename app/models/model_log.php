<?php

defined('LETTER') || exit('NewsLetter: access denied.');


class Model_log extends Model
{
    /**
     * @param int $pnumber
     * @param $page
     * @return mixed
     */
    public function getLogArr($pnumber = 10, $page)
    {
        $table = core::database()->getTableName('log');
        core::database()->parameters = "*,DATE_FORMAT(time,'%d.%m.%Y %H:%i') as send_time";
        core::database()->tablename = core::database()->getTableName('log');
        core::database()->order = 'ORDER BY id_log desc';
        core::database()->pnumber = $pnumber;
        core::database()->page = $page;
        return core::database()->get_page();
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        core::database()->tablename = core::database()->getTableName('log');
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

    /**
     * @param $strtmp
     * @param $id_log
     * @param int $number
     * @return mixed
     */
    public function getDetaillog($strtmp, $id_log, $number = 10)
    {
        if (is_numeric($id_log)) {
            $query = "SELECT *, a.time AS time, c.name AS catname, s.name AS name FROM " . core::database()->getTableName('ready_send') . " a
					    LEFT JOIN " . core::database()->getTableName('template') . " s ON a.id_template=s.id_template
					    LEFT JOIN " . core::database()->getTableName('category') . " c ON s.id_cat=c.id_cat
					    WHERE id_log=" . $id_log . "
					    ORDER BY " . $strtmp . "
					    LIMIT " . $number;

            $result = core::database()->querySQL($query);
            return core::database()->getColumnArray($result);
        }
    }

    /**
     * @param $id_log
     * @return mixed
     */
    public function countLetters($id_log)
    {
        if (is_numeric($id_log)) {
            $query = "SELECT * FROM " . core::database()->getTableName('ready_send') . " WHERE id_log=" . $id_log;
            $result = core::database()->querySQL($query);
            return core::database()->getRecordCount($result);
        }
    }

    /**
     * @param $id_log
     * @return mixed
     */
    public function countSent($id_log)
    {
        if(is_numeric($id_log)) {
            $query = "SELECT * FROM " . core::database()->getTableName('ready_send') . " WHERE success='yes' and id_log=" . $id_log;
            $result = core::database()->querySQL($query);
            return core::database()->getRecordCount($result);
        }
    }

    /**
     * @param $id_log
     * @return mixed
     */
    public function countRead($id_log)
    {
        $id_log = core::database()->escape($id_log);
        $query = "SELECT * FROM " . core::database()->getTableName('ready_send') . " WHERE readmail='yes' and id_log=" . $id_log;
        $result = core::database()->querySQL($query);
        return core::database()->getRecordCount($result);
    }

    /**
     * @return bool
     */
    public function clearLog()
    {
        core::session()->start();
        core::session()->delete('id_log');
        core::session()->commit();

        $delete1 = core::database()->delete(core::database()->getTableName('log'));
        $delete2 = core::database()->delete(core::database()->getTableName('ready_send'));
        
        if ($delete1 && $delete2)
            return true;
        else
            return false;
    }
}