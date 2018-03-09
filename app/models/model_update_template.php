<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_update_template extends Model
{
    /**
     * @return mixed
     */
    public function saveTemplate($id_template, $body)
    {
        if (is_numeric($id_template)) {
            $mBody = $this->manipulation($body);
            $query = "UPDATE " . core::database()->getTableName('template') . " SET body_temp='{$body}', body='{$mBody}' WHERE id_template=" . $id_template;
            return core::database()->querySQL($query);
        }
    }

    /**
     * @param $id_template
     * @return mixed
     */
    public function getTemplate($id_template)
    {
        if (is_numeric($id_template)) {
            $query = "SELECT * FROM " . core::database()->getTableName('template') . " WHERE id_template=" . $id_template;
            $result = core::database()->querySQL($query);

            return core::database()->getRow($result);
        }
    }
    
    private function manipulation($html){
        $doc = new \DOMDocument();
        @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $links = $doc->getElementsByTagName('a');

        foreach ($links as $a) {
            $muteLink = $a->getAttribute('class');
            if ('mute-link' !== $muteLink) {
                $href = "http://newsletter.independen.id/profile.php?id=%USERID%&amp;token=%USERTOKEN%&amp;url=".$a->getAttribute('href');
                $a->setAttribute('href', $href);    
            }
        }
        
        return $doc->saveHtml();
    }
}