<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Pnl
{
    public static function getRandomCode() {
        $maxcount = 32;
        $rand = "0123456789abcdef";
        srand((double) microtime() * 1000000);
        $RandCode = "";
        for ($count = 0; $count < $maxcount; $count ++)
            $RandCode .= substr($rand, rand(1, 15), 1);

        return $RandCode;
    }

    public static function error($msg)
    {
        echo "<!DOCTYPE html>\n";
        echo "<html>\n";
        echo "<head>\n";
        echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">\n";
        echo "</head>\n";
        echo "<body>\n";
        echo "<p>" . $msg . "</p>\n";
        echo "</body>\n";
        echo "</html>";
        exit();
    }

    public static function root() {
        if (dirname($_SERVER['SCRIPT_NAME']) == '/' | dirname($_SERVER['SCRIPT_NAME']) == '\\')
            return '/';
        else
            return dirname($_SERVER['SCRIPT_NAME']) . '/';
    }

    public static function check_email($email) {
        if (preg_match("/^([a-z0-9_\.\-]{1,70})@([a-z0-9\.\-]{1,70})\.([a-z]{2,6})$/i", $email))
            return false;
        else
            return true;
    }

    public static function charsetlist($str)
    {
        $str = preg_replace("/^utf\-8$/i", core::getLanguage('str', 'charutf8'), $str);
        $str = preg_replace("/^iso\-8859\-1$/i", core::getLanguage('str', 'iso88591'), $str);
        $str = preg_replace("/^iso\-8859\-2$/i", core::getLanguage('str', 'iso88592'), $str);
        $str = preg_replace("/^iso\-8859\-3$/i", core::getLanguage('str', 'iso88593'), $str);
        $str = preg_replace("/^iso\-8859\-4$/i", core::getLanguage('str', 'iso88594'), $str);
        $str = preg_replace("/^iso\-8859\-5$/i", core::getLanguage('str', 'iso88595'), $str);
        $str = preg_replace("/^koi8\-r$/i", core::getLanguage('str', 'koi8r'), $str);
        $str = preg_replace("/^koi8\-u$/i", core::getLanguage('str', 'koi8u'), $str);
        $str = preg_replace("/^iso\-8859\-6$/i", core::getLanguage('str', 'iso88596'), $str);
        $str = preg_replace("/^iso\-8859\-8$/i", core::getLanguage('str', 'iso88598'), $str);
        $str = preg_replace("/^iso\-8859\-7$/i", core::getLanguage('str', 'iso88597'), $str);
        $str = preg_replace("/^iso\-8859\-9$/i", core::getLanguage('str', 'ISO88599'), $str);
        $str = preg_replace("/^iso\-8859\-10$/i", core::getLanguage('str', 'iso885910'), $str);
        $str = preg_replace("/^iso\-8859\-13$/i", core::getLanguage('str', 'iso885913'), $str);
        $str = preg_replace("/^iso\-8859\-14$/i", core::getLanguage('str', 'iso885914'), $str);
        $str = preg_replace("/^iso\-8859\-15$/i", core::getLanguage('str', 'iso885915'), $str);
        $str = preg_replace("/^iso\-8859\-16$/i", core::getLanguage('str', 'iso885916'), $str);
        $str = preg_replace("/^windows\-1250$/i", core::getLanguage('str', 'windows1250'), $str);
        $str = preg_replace("/^windows\-1251$/i", core::getLanguage('str', 'windows1251'), $str);
        $str = preg_replace("/^windows\-1252$/i", core::getLanguage('str', 'windows1252'), $str);
        $str = preg_replace("/^windows\-1253$/i", core::getLanguage('str', 'windows1253'), $str);
        $str = preg_replace("/^windows\-1254$/i", core::getLanguage('str', 'windows1254'), $str);
        $str = preg_replace("/^windows\-1255$/i", core::getLanguage('str', 'windows1255'), $str);
        $str = preg_replace("/^windows\-1256$/i", core::getLanguage('str', 'windows1256'), $str);
        $str = preg_replace("/^windows\-1257$/i", core::getLanguage('str', 'windows1257'), $str);
        $str = preg_replace("/^windows\-1258$/i", core::getLanguage('str', 'windows1258'), $str);
        $str = preg_replace("/^gb2312$/i", core::getLanguage('str', 'gb2312'), $str);
        $str = preg_replace("/^big5$/i", core::getLanguage('str', 'big5'), $str);
        $str = preg_replace("/^iso-2022\-jp$/i", core::getLanguage('str', 'iso2022jp'), $str);
        $str = preg_replace("/^ks_c_5601\-1987$/i", core::getLanguage('str', 'ksc56011987'), $str);
        $str = preg_replace("/^euc\-kr$/i", core::getLanguage('str', 'euckr'), $str);
        $str = preg_replace("/^windows\-874$/i", core::getLanguage('str', 'windows874'), $str);

        return $str;
    }

    public static function remove_html_tags($str) {
        $tags = array(
            "/<script[^>]*?>.*?<\/script>/si",
            "/<[\/\!]*?[^<>]*?>/si",
            "/&(quot|#34);/i",
            "/&(laquo|#171);/i",
            "/&(raquo|#187);/i",
            "/&(hellip|#8230);/i",
            "/&(amp|#38);/i",
            "/&(lt|#60);/i",
            "/&(gt|#62);/i",
            "/&(nbsp|#160);/i",
            "/&(iexcl|#161);/i",
            "/&(cent|#162);/i",
            "/&(pound|#163);/i",
            "/&(copy|#169);/i"
        );

        $replace = array(
            "",
            "",
            "\"",
            "\"",
            "\"",
            "...",
            "&",
            "<",
            ">",
            " ",
            chr(161),
            chr(162),
            chr(163),
            chr(169)
        );

        $str = preg_replace($tags, $replace, $str);

        return $str;
    }

    public static function get_mime_type($ext) {
        $mimetypes = Array(
            "123" => "application/vnd.lotus-1-2-3",
            "3ds" => "image/x-3ds",
            "669" => "audio/x-mod",
            "a" => "application/x-archive",
            "abw" => "application/x-abiword",
            "ac3" => "audio/ac3",
            "adb" => "text/x-adasrc",
            "ads" => "text/x-adasrc",
            "afm" => "application/x-font-afm",
            "ag" => "image/x-applix-graphics",
            "ai" => "application/illustrator",
            "aif" => "audio/x-aiff",
            "aifc" => "audio/x-aiff",
            "aiff" => "audio/x-aiff",
            "al" => "application/x-perl",
            "arj" => "application/x-arj",
            "as" => "application/x-applix-spreadsheet",
            "asc" => "text/plain",
            "asf" => "video/x-ms-asf",
            "asp" => "application/x-asp",
            "asx" => "video/x-ms-asf",
            "au" => "audio/basic",
            "avi" => "video/x-msvideo",
            "aw" => "application/x-applix-word",
            "bak" => "application/x-trash",
            "bcpio" => "application/x-bcpio",
            "bdf" => "application/x-font-bdf",
            "bib" => "text/x-bibtex",
            "bin" => "application/octet-stream",
            "blend" => "application/x-blender",
            "blender" => "application/x-blender",
            "bmp" => "image/bmp",
            "bz" => "application/x-bzip",
            "bz2" => "application/x-bzip",
            "c" => "text/x-csrc",
            "c++" => "text/x-c++src",
            "cc" => "text/x-c++src",
            "cdf" => "application/x-netcdf",
            "cdr" => "application/vnd.corel-draw",
            "cer" => "application/x-x509-ca-cert",
            "cert" => "application/x-x509-ca-cert",
            "cgi" => "application/x-cgi",
            "cgm" => "image/cgm",
            "chrt" => "application/x-kchart",
            "class" => "application/x-java",
            "cls" => "text/x-tex",
            "cpio" => "application/x-cpio",
            "cpp" => "text/x-c++src",
            "crt" => "application/x-x509-ca-cert",
            "cs" => "text/x-csharp",
            "csh" => "application/x-shellscript",
            "css" => "text/css",
            "cssl" => "text/css",
            "csv" => "text/x-comma-separated-values",
            "cur" => "image/x-win-bitmap",
            "cxx" => "text/x-c++src",
            "dat" => "video/mpeg",
            "dbf" => "application/x-dbase",
            "dc" => "application/x-dc-rom",
            "dcl" => "text/x-dcl",
            "dcm" => "image/x-dcm",
            "deb" => "application/x-deb",
            "der" => "application/x-x509-ca-cert",
            "desktop" => "application/x-desktop",
            "dia" => "application/x-dia-diagram",
            "diff" => "text/x-patch",
            "djv" => "image/vnd.djvu",
            "djvu" => "image/vnd.djvu",
            "doc" => "application/vnd.ms-word",
            "dsl" => "text/x-dsl",
            "dtd" => "text/x-dtd",
            "dvi" => "application/x-dvi",
            "dwg" => "image/vnd.dwg",
            "dxf" => "image/vnd.dxf",
            "egon" => "application/x-egon",
            "el" => "text/x-emacs-lisp",
            "eps" => "image/x-eps",
            "epsf" => "image/x-eps",
            "epsi" => "image/x-eps",
            "etheme" => "application/x-e-theme",
            "etx" => "text/x-setext",
            "exe" => "application/x-ms-dos-executable",
            "ez" => "application/andrew-inset",
            "f" => "text/x-fortran",
            "fig" => "image/x-xfig",
            "fits" => "image/x-fits",
            "flac" => "audio/x-flac",
            "flc" => "video/x-flic",
            "fli" => "video/x-flic",
            "flw" => "application/x-kivio",
            "fo" => "text/x-xslfo",
            "g3" => "image/fax-g3",
            "gb" => "application/x-gameboy-rom",
            "gcrd" => "text/x-vcard",
            "gen" => "application/x-genesis-rom",
            "gg" => "application/x-sms-rom",
            "gif" => "image/gif",
            "glade" => "application/x-glade",
            "gmo" => "application/x-gettext-translation",
            "gnc" => "application/x-gnucash",
            "gnucash" => "application/x-gnucash",
            "gnumeric" => "application/x-gnumeric",
            "gra" => "application/x-graphite",
            "gsf" => "application/x-font-type1",
            "gtar" => "application/x-gtar",
            "gz" => "application/x-gzip",
            "h" => "text/x-chdr",
            "h++" => "text/x-chdr",
            "hdf" => "application/x-hdf",
            "hh" => "text/x-c++hdr",
            "hp" => "text/x-chdr",
            "hpgl" => "application/vnd.hp-hpgl",
            "hs" => "text/x-haskell",
            "htm" => "text/html",
            "html" => "text/html",
            "icb" => "image/x-icb",
            "ico" => "image/x-ico",
            "ics" => "text/calendar",
            "idl" => "text/x-idl",
            "ief" => "image/ief",
            "iff" => "image/x-iff",
            "ilbm" => "image/x-ilbm",
            "iso" => "application/x-cd-image",
            "it" => "audio/x-it",
            "jar" => "application/x-jar",
            "java" => "text/x-java",
            "jng" => "image/x-jng",
            "jp2" => "image/jpeg2000",
            "jpe" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "jpg" => "image/jpeg",
            "jpr" => "application/x-jbuilder-project",
            "jpx" => "application/x-jbuilder-project",
            "js" => "application/x-javascript",
            "karbon" => "application/x-karbon",
            "kdelnk" => "application/x-desktop",
            "kfo" => "application/x-kformula",
            "kil" => "application/x-killustrator",
            "kon" => "application/x-kontour",
            "kpm" => "application/x-kpovmodeler",
            "kpr" => "application/x-kpresenter",
            "kpt" => "application/x-kpresenter",
            "kra" => "application/x-krita",
            "ksp" => "application/x-kspread",
            "kud" => "application/x-kugar",
            "kwd" => "application/x-kword",
            "kwt" => "application/x-kword",
            "la" => "application/x-shared-library-la",
            "lha" => "application/x-lha",
            "lhs" => "text/x-literate-haskell",
            "lhz" => "application/x-lhz",
            "log" => "text/x-log",
            "ltx" => "text/x-tex",
            "lwo" => "image/x-lwo",
            "lwob" => "image/x-lwo",
            "lws" => "image/x-lws",
            "lyx" => "application/x-lyx",
            "lzh" => "application/x-lha",
            "lzo" => "application/x-lzop",
            "m" => "text/x-objcsrc",
            "m15" => "audio/x-mod",
            "m3u" => "audio/x-mpegurl",
            "man" => "application/x-troff-man",
            "md" => "application/x-genesis-rom",
            "me" => "text/x-troff-me",
            "mgp" => "application/x-magicpoint",
            "mid" => "audio/midi",
            "midi" => "audio/midi",
            "mif" => "application/x-mif",
            "mkv" => "application/x-matroska",
            "mm" => "text/x-troff-mm",
            "mml" => "text/mathml",
            "mng" => "video/x-mng",
            "moc" => "text/x-moc",
            "mod" => "audio/x-mod",
            "moov" => "video/quicktime",
            "mov" => "video/quicktime",
            "movie" => "video/x-sgi-movie",
            "mp2" => "video/mpeg",
            "mp3" => "audio/x-mp3",
            "mpe" => "video/mpeg",
            "mpeg" => "video/mpeg",
            "mpg" => "video/mpeg",
            "ms" => "text/x-troff-ms",
            "msod" => "image/x-msod",
            "msx" => "application/x-msx-rom",
            "mtm" => "audio/x-mod",
            "n64" => "application/x-n64-rom",
            "nc" => "application/x-netcdf",
            "nes" => "application/x-nes-rom",
            "nsv" => "video/x-nsv",
            "o" => "application/x-object",
            "obj" => "application/x-tgif",
            "oda" => "application/oda",
            "ogg" => "application/ogg",
            "old" => "application/x-trash",
            "oleo" => "application/x-oleo",
            "p" => "text/x-pascal",
            "p12" => "application/x-pkcs12",
            "p7s" => "application/pkcs7-signature",
            "pas" => "text/x-pascal",
            "patch" => "text/x-patch",
            "pbm" => "image/x-portable-bitmap",
            "pcd" => "image/x-photo-cd",
            "pcf" => "application/x-font-pcf",
            "pcl" => "application/vnd.hp-pcl",
            "pdb" => "application/vnd.palm",
            "pdf" => "application/pdf",
            "pem" => "application/x-x509-ca-cert",
            "perl" => "application/x-perl",
            "pfa" => "application/x-font-type1",
            "pfb" => "application/x-font-type1",
            "pfx" => "application/x-pkcs12",
            "pgm" => "image/x-portable-graymap",
            "pgn" => "application/x-chess-pgn",
            "pgp" => "application/pgp",
            "php" => "application/x-php",
            "php3" => "application/x-php",
            "php4" => "application/x-php",
            "pict" => "image/x-pict",
            "pict1" => "image/x-pict",
            "pict2" => "image/x-pict",
            "pl" => "application/x-perl",
            "pls" => "audio/x-scpls",
            "pm" => "application/x-perl",
            "png" => "image/png",
            "pnm" => "image/x-portable-anymap",
            "po" => "text/x-gettext-translation",
            "pot" => "text/x-gettext-translation-template",
            "ppm" => "image/x-portable-pixmap",
            "pps" => "application/vnd.ms-powerpoint",
            "ppt" => "application/vnd.ms-powerpoint",
            "ppz" => "application/vnd.ms-powerpoint",
            "ps" => "application/postscript",
            "psd" => "image/x-psd",
            "psf" => "application/x-font-linux-psf",
            "psid" => "audio/prs.sid",
            "pw" => "application/x-pw",
            "py" => "application/x-python",
            "pyc" => "application/x-python-bytecode",
            "pyo" => "application/x-python-bytecode",
            "qif" => "application/x-qw",
            "qt" => "video/quicktime",
            "qtvr" => "video/quicktime",
            "ra" => "audio/x-pn-realaudio",
            "ram" => "audio/x-pn-realaudio",
            "rar" => "application/x-rar",
            "ras" => "image/x-cmu-raster",
            "rdf" => "text/rdf",
            "rej" => "application/x-reject",
            "rgb" => "image/x-rgb",
            "rle" => "image/rle",
            "rm" => "audio/x-pn-realaudio",
            "roff" => "application/x-troff",
            "rpm" => "application/x-rpm",
            "rss" => "text/rss",
            "rtf" => "application/rtf",
            "rtx" => "text/richtext",
            "s3m" => "audio/x-s3m",
            "sam" => "application/x-amipro",
            "scm" => "text/x-scheme",
            "sda" => "application/vnd.stardivision.draw",
            "sdc" => "application/vnd.stardivision.calc",
            "sdd" => "application/vnd.stardivision.impress",
            "sdp" => "application/vnd.stardivision.impress",
            "sds" => "application/vnd.stardivision.chart",
            "sdw" => "application/vnd.stardivision.writer",
            "sgi" => "image/x-sgi",
            "sgl" => "application/vnd.stardivision.writer",
            "sgm" => "text/sgml",
            "sgml" => "text/sgml",
            "sh" => "application/x-shellscript",
            "shar" => "application/x-shar",
            "siag" => "application/x-siag",
            "sid" => "audio/prs.sid",
            "sik" => "application/x-trash",
            "slk" => "text/spreadsheet",
            "smd" => "application/vnd.stardivision.mail",
            "smf" => "application/vnd.stardivision.math",
            "smi" => "application/smil",
            "smil" => "application/smil",
            "sml" => "application/smil",
            "sms" => "application/x-sms-rom",
            "snd" => "audio/basic",
            "so" => "application/x-sharedlib",
            "spd" => "application/x-font-speedo",
            "sql" => "text/x-sql",
            "src" => "application/x-wais-source",
            "stc" => "application/vnd.sun.xml.calc.template",
            "std" => "application/vnd.sun.xml.draw.template",
            "sti" => "application/vnd.sun.xml.impress.template",
            "stm" => "audio/x-stm",
            "stw" => "application/vnd.sun.xml.writer.template",
            "sty" => "text/x-tex",
            "sun" => "image/x-sun-raster",
            "sv4cpio" => "application/x-sv4cpio",
            "sv4crc" => "application/x-sv4crc",
            "svg" => "image/svg+xml",
            "swf" => "application/x-shockwave-flash",
            "sxc" => "application/vnd.sun.xml.calc",
            "sxd" => "application/vnd.sun.xml.draw",
            "sxg" => "application/vnd.sun.xml.writer.global",
            "sxi" => "application/vnd.sun.xml.impress",
            "sxm" => "application/vnd.sun.xml.math",
            "sxw" => "application/vnd.sun.xml.writer",
            "sylk" => "text/spreadsheet",
            "t" => "application/x-troff",
            "tar" => "application/x-tar",
            "tcl" => "text/x-tcl",
            "tcpalette" => "application/x-terminal-color-palette",
            "tex" => "text/x-tex",
            "texi" => "text/x-texinfo",
            "texinfo" => "text/x-texinfo",
            "tga" => "image/x-tga",
            "tgz" => "application/x-compressed-tar",
            "theme" => "application/x-theme",
            "tif" => "image/tiff",
            "tiff" => "image/tiff",
            "tk" => "text/x-tcl",
            "torrent" => "application/x-bittorrent",
            "tr" => "application/x-troff",
            "ts" => "application/x-linguist",
            "tsv" => "text/tab-separated-values",
            "ttf" => "application/x-font-ttf",
            "txt" => "text/plain",
            "tzo" => "application/x-tzo",
            "ui" => "application/x-designer",
            "uil" => "text/x-uil",
            "ult" => "audio/x-mod",
            "uni" => "audio/x-mod",
            "uri" => "text/x-uri",
            "url" => "text/x-uri",
            "ustar" => "application/x-ustar",
            "vcf" => "text/x-vcalendar",
            "vcs" => "text/x-vcalendar",
            "vct" => "text/x-vcard",
            "vob" => "video/mpeg",
            "voc" => "audio/x-voc",
            "vor" => "application/vnd.stardivision.writer",
            "vpp" => "application/x-extension-vpp",
            "wav" => "audio/x-wav",
            "wb1" => "application/x-quattropro",
            "wb2" => "application/x-quattropro",
            "wb3" => "application/x-quattropro",
            "wk1" => "application/vnd.lotus-1-2-3",
            "wk3" => "application/vnd.lotus-1-2-3",
            "wk4" => "application/vnd.lotus-1-2-3",
            "wks" => "application/vnd.lotus-1-2-3",
            "wmf" => "image/x-wmf",
            "wml" => "text/vnd.wap.wml",
            "wmv" => "video/x-ms-wmv",
            "wpd" => "application/vnd.wordperfect",
            "wpg" => "application/x-wpg",
            "wri" => "application/x-mswrite",
            "wrl" => "model/vrml",
            "xac" => "application/x-gnucash",
            "xbel" => "application/x-xbel",
            "xbm" => "image/x-xbitmap",
            "xcf" => "image/x-xcf",
            "xhtml" => "application/xhtml+xml",
            "xi" => "audio/x-xi",
            "xla" => "application/vnd.ms-excel",
            "xlc" => "application/vnd.ms-excel",
            "xld" => "application/vnd.ms-excel",
            "xll" => "application/vnd.ms-excel",
            "xlm" => "application/vnd.ms-excel",
            "xls" => "application/vnd.ms-excel",
            "xlt" => "application/vnd.ms-excel",
            "xlw" => "application/vnd.ms-excel",
            "xm" => "audio/x-xm",
            "xmi" => "text/x-xmi",
            "xml" => "text/xml",
            "xpm" => "image/x-xpixmap",
            "xsl" => "text/x-xslt",
            "xslfo" => "text/x-xslfo",
            "xslt" => "text/x-xslt",
            "xwd" => "image/x-xwindowdump",
            "z" => "application/x-compress",
            "zabw" => "application/x-abiword",
            "zip" => "application/zip",
            "zoo" => "application/x-zoo"
        );

        $ext = trim(strtolower($ext));

        if ($ext != '' && isset($mimetypes[$ext])) {
            return $mimetypes[$ext];
        } else {
            return "application/force-download";
        }
    }

    public static function getIP() {
        if (getenv("HTTP_CLIENT_IP") and strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        elseif (getenv("REMOTE_ADDR") and strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        elseif (! empty($_SERVER['REMOTE_ADDR']) and strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";

        return ($ip);
    }

    public static function showJSONContent($content) {
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Content-Type: application/json');
        echo $content;
        exit();
    }
	
	public function get_current_version_code($version)
	{
		preg_match("/(\d+)\.(\d+)\./", $version, $out);
		
		$current_version_code = ($out[1] * 10000 + $out[2] * 100);
	
		return $current_version_code;
	}

    public static function CheckAccess($role, $str)
    {
        $arr = explode(",", $str);

        foreach ($arr as $key => $val) {
            $arr[$key] = trim($val);
        }

        if (in_array($role, $arr))
            return FALSE;
        else
            return TRUE;
    }

    static public function file_get_contents_curl($url, $timeout = 10)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        $data = curl_exec($ch);

        curl_close($ch);

        preg_match('/\{([^\}])+\}/U',$data, $out);
        return $out[0];
    }

    static public function sys_error_msg($msg)
    {
        $msg = str_replace('CANNT_CREATE_LICENSEKEY_FILE', core::getLanguage('msg', 'cannt_create_licensekey_file'), $msg);
        $msg = str_replace('ERROR_CHECK_LICENSEKEY', core::getLanguage('msg', 'error_check_licensekey'), $msg);

        return $msg;
    }

    static public function checkLicensekey($licensekey)
    {
        $domain = (substr($_SERVER['SERVER_NAME'], 0, 4)) == "www." ? str_replace('www.','', $_SERVER['SERVER_NAME']) : $_SERVER['SERVER_NAME'];
        $url = 'http://rijalasepnugroho.com';

        $data = self::file_get_contents_curl($url, 5);

        if ($data)  {
            return json_decode($data, true);
        } else {
            return array('error' => 'ERROR_CHECKING_LICENSE');
        }
    }
}