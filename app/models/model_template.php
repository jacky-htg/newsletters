<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_template extends Model
{
    /**
     * @param $pnumber
     * @param $page
     * @return mixed
     */
    public function getListArr($pnumber, $page)
    {
        $table = "" . core::database()->getTableName('template') . " tmpl LEFT JOIN " . core::database()->getTableName('category') . " cat ON cat.id_cat=tmpl.id_cat";
        core::database()->parameters = "*,cat.name as catname, tmpl.name as tmplname";
        core::database()->tablename = $table;
        core::database()->order = 'ORDER BY pos DESC';
        core::database()->pnumber = $pnumber;
        core::database()->page = $page;
        return core::database()->get_page();
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        core::database()->tablename = core::database()->getTableName('template');
        return intval((core::database()->get_total() - 1) / core::database()->pnumber) + 1;
    }

    /**
     * @return mixed
     */
    public function getPageNumber()
    {
        return core::database()->page;
    }

    /**
     * @param $fields
     * @param $activate
     * @return mixed
     */
    public function changeStatusNewsLetter($fields, $activate)
    {
        $temp = array();
        foreach ($activate as $id) {
            if (is_numeric($id))
                $temp[] = $id;
        }
        $table = core::database()->getTableName('template');
        $where = "id_template IN (" . implode(",", $temp) . ")";
        $result = core::database()->update($fields, $table, $where);
        
        unset($temp);
        return $result;
    }

    /**
     * @param $activate
     * @return bool
     */
    public function removeTemplate($activate)
    {
        if (!empty($activate)) {
            $temp = array();

            foreach ($activate as $id) {
                if (is_numeric($id)) $temp[] = $id;
            }

            $query = "SELECT * FROM " . core::database()->getTableName('attach') . " WHERE id_template IN (" . implode(",", $temp) . ")";
            $result = core::database()->querySQL($query);
            
            $arr = core::database()->getColumnArray($result);

            if (is_array($arr)) {
                for ($i = 0; $i < count($arr); $i++) {
                    if (file_exists($arr[$i]['path'])) @unlink($arr[$i]['path']);
                }
            }
            
            $result = core::database()->delete(core::database()->getTableName('template'), "id_template IN (" . implode(",", $temp) . ")", '');
            unset($temp);

            return $result;
        } else
            return false;
    }

    /**
     * @param $id_template
     * @return bool
     */
    public function upPosition($id_template)
    {
        $query = "SELECT * FROM " . core::database()->getTableName('template') . " ORDER BY pos";
        $result = core::database()->querySQL($query);

        while($row = core::database()->getRow($result)) {
            if ($row["id_template"] == $id_template) {
                $pos = $row["pos"];
                $row = core::database()->getRow($result);
                $id_next = $row["id_template"];
                $posnext = $row["pos"];
            }
        }

        if ($id_next) {
            $update1 = "UPDATE " . core::database()->getTableName('template') . " SET pos=" . $pos . " WHERE id_template=" . $id_next;
            $update2 = "UPDATE " . core::database()->getTableName('template') . " SET pos=" . $posnext . " WHERE id_template=" . $id_template;

            if (core::database()->querySQL($update1) && core::database()->querySQL($update2))
                return true;
            else
                return false;
        }
        else return true;
    }

    /**
     * @param $id_template
     * @return bool
     */
    public function downPosition($id_template)
    {
        $query = "SELECT * FROM " . core::database()->getTableName('template') . " ORDER BY pos DESC";
        $result = core::database()->querySQL($query);

        while($row = core::database()->getRow($result)) {
            if ($row["id_template"] == $id_template) {
                $pos = $row["pos"];
                $row = core::database()->getRow($result);
                $id_next = $row["id_template"];
                $posnext = $row["pos"];
            }
        }

        if ($id_next) {
            $update1 = "UPDATE " . core::database()->getTableName('template') . " SET pos=" . $pos . " WHERE id_template=" . $id_next;
            $update2 = "UPDATE " . core::database()->getTableName('template') . " SET pos=" . $posnext . " WHERE id_template=" . $id_template;

            if (core::database()->querySQL($update1) && core::database()->querySQL($update2))
                return true;
            else
                return false;
        }
        else return true;
    }
}