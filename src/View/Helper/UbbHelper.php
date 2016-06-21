<?php
/**
 * Dublin(tm) : Bulletin board software (https://github.com/PropertyX)
 * Copyright (c) PropertyX Software Foundation, Inc. (https://github.com/Property)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) PropertyX Software Foundation, Inc. (https://github.com/PropertyX)
 * @link      https://github.com/PropertyX
 * @since     1.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Core\Configure;
use Emojione\Client;
use Emojione\Emojione;
use Emojione\Ruleset;

class UbbHelper extends Helper
{

    private static $smileyIcon;

    public static function parse($string, $emojione = true)
    {
        self::getSmilies();

        $string = stripslashes($string);
        $string = nl2br($string);

        $string = self::setUbb($string);
        $string = ((true) ? self::smilies($string) : $string);

        $string = (($emojione) ? self::emo($string) : $string);
        $string = str_replace("bmdiv", "div", $string);

        return $string;
    }

    public static function emo($string)
    {
        $client = new Client(new Ruleset());
        $client->imageType = 'svg';

        return $client->toImage($string);
    }

    public static function smilies($string)
    {
        foreach(self::$smileyIcon as $key => $value)
        {
            $string = str_replace($key, '<img class="emojione" src="//cdn.jsdelivr.net/emojione/assets/png/'  . $value . '.png" alt="' . $key . '" title="' . $key . '" />', $string);
        }

        return $string;
    }

    public static function getSmilies()
    {
        self::$smileyIcon = [
            '<3' => '2764',
            '</3' => '1f494',
            ':\')' => '1f602',
            ':\'-)' => '1f602',
            ':D' => '1f603',
            ':-D' => '1f603',
            '=D' => '1f603',
            ':)' => '1f642',
            ':-)' => '1f642',
            '=]' => '1f642',
            '=)' => '1f642',
            ':]' => '1f642',
            '\':)' => '1f605',
            '\':-)' => '1f605',
            '\'=)' => '1f605',
            '\':D' => '1f605',
            '\':-D' => '1f605',
            '\'=D' => '1f605',
            '>:)' => '1f606',
            '>;)' => '1f606',
            '>:-)' => '1f606',
            '>=)' => '1f606',
            ';)' => '1f609',
            ';-)' => '1f609',
            '*-)' => '1f609',
            '*)' => '1f609',
            ';-]' => '1f609',
            ';]' => '1f609',
            ';D' => '1f609',
            ';^)' => '1f609',
            '\':(' => '1f613',
            '\':-(' => '1f613',
            '\'=(' => '1f613',
            ':*' => '1f618',
            ':-*' => '1f618',
            '=*' => '1f618',
            ':^*' => '1f618',
            '>:P' => '1f61c',
            'X-P' => '1f61c',
            'x-p' => '1f61c',
            '>:[' => '1f61e',
            ':-(' => '1f61e',
            ':(' => '1f61e',
            ':-[' => '1f61e',
            ':[' => '1f61e',
            '=(' => '1f61e',
            '>:(' => '1f620',
            '>:-(' => '1f620',
            ':@' => '1f620',
            ':\'(' => '1f622',
            ':\'-(' => '1f622',
            ';(' => '1f622',
            ';-(' => '1f622',
            '>.<' => '1f623',
            'D:' => '1f628',
            ':$' => '1f633',
            '=$' => '1f633',
            '#-)' => '1f635',
            '#)' => '1f635',
            '%-)' => '1f635',
            '%)' => '1f635',
            'X)' => '1f635',
            'X-)' => '1f635',
            '*\\0/*' => '1f646',
            '\\0/' => '1f646',
            '*\\O/*' => '1f646',
            '\\O/' => '1f646',
            'O:-)' => '1f607',
            '0:-3' => '1f607',
            '0:3' => '1f607',
            '0:-)' => '1f607',
            '0:)' => '1f607',
            '0;^)' => '1f607',
            'O:)' => '1f607',
            'O;-)' => '1f607',
            'O=)' => '1f607',
            '0;-)' => '1f607',
            'O:-3' => '1f607',
            'O:3' => '1f607',
            'B-)' => '1f60e',
            'B)' => '1f60e',
            '8)' => '1f60e',
            '8-)' => '1f60e',
            'B-D' => '1f60e',
            '8-D' => '1f60e',
            '-_-' => '1f611',
            '-__-' => '1f611',
            '-___-' => '1f611',
            '>:\\' => '1f615',
            '>:/' => '1f615',
            ':-/' => '1f615',
            ':-.' => '1f615',
            '=/' => '1f615',
            '=\\' => '1f615',
            ':L' => '1f615',
            '=L' => '1f615',
            ':P' => '1f61b',
            ':-P' => '1f61b',
            '=P' => '1f61b',
            ':-p' => '1f61b',
            ':p' => '1f61b',
            '=p' => '1f61b',
            ':-Þ' => '1f61b',
            ':Þ' => '1f61b',
            ':þ' => '1f61b',
            ':-þ' => '1f61b',
            ':-b' => '1f61b',
            ':b' => '1f61b',
            'd:' => '1f61b',
            ':-O' => '1f62e',
            ':O' => '1f62e',
            ':-o' => '1f62e',
            ':o' => '1f62e',
            'O_O' => '1f62e',
            '>:O' => '1f62e',
            ':-X' => '1f636',
            ':X' => '1f636',
            ':-#' => '1f636',
            ':#' => '1f636',
            '=X' => '1f636',
            '=x' => '1f636',
            ':x' => '1f636',
            ':-x' => '1f636',
            '=#' => '1f636'
        ];
    }

    public static function setUbb($bbcode)
    {
        $bbcode = preg_replace("_\[b\](.*?)\[/b\]_si", '<b>$1</b>', $bbcode);
        $bbcode = preg_replace('_\[i\](.*?)\[/i\]_si', '<i>$1</i>', $bbcode);
        $bbcode = str_replace("[hr]", '<hr />', $bbcode);
        $bbcode = preg_replace("_\[h3\](.*?)\[/h3\]_si", '<h3>$1</h3>', $bbcode);
        $bbcode = preg_replace("_\[u\](.*?)\[/u\]_si", '<u>$1</u>', $bbcode);
        $bbcode = preg_replace("_\[center\](.*?)\[/center\]_si", '<center>$1</center>', $bbcode);
        $bbcode = preg_replace("_\[list\](.*?)\[/list\]_si", '<ul>$1</ul>', $bbcode);
        $bbcode = preg_replace("_\[item\](.*?)\[/item\]_si", '<li>$1</li>', $bbcode);
        $bbcode = preg_replace("_\[s\](.*?)\[/s\]_si", '<strike>$1</strike>', $bbcode);
        $bbcode = preg_replace("_\[size=(.*?)\](.*?)\[/size\]_si", '<span style="font-size: $1px">$2</span>', $bbcode);
        $bbcode = preg_replace("_\[color=(.*?)\](.*?)\[/color\]_si", '<span style="color: $1;">$2</span>', $bbcode);
        $bbcode = preg_replace("_\[font=(.*?)\](.*?)\[/font\]_si", '<span style="font-family: $1">$2</span>', $bbcode);
        $bbcode = preg_replace("_\[hl=(.*?)\](.*?)\[/hl\]_si", '<span style="background: $1">$2</span>', $bbcode);
        $bbcode = preg_replace("_\[align=center\](.*?)\[/align\]_si", '<div id="centerBlock">$1</div>', $bbcode);
        $bbcode = preg_replace("_\[align=left\](.*?)\[/align\]_si", '<div id="leftBlock">$1</div>', $bbcode);
        $bbcode = preg_replace("_\[align=right\](.*?)\[/align\]_si", '<div id="rightBlock">$1</div>', $bbcode);
        $bbcode = preg_replace("_\[youtube\](.*?)\[/youtube\]_si", '<iframe src="$1" id="ytBlock"></iframe>', $bbcode);
        $bbcode = preg_replace("_\[yt\](.*?)\[/yt\]_si", '<iframe src="$1" id="ytBlock"></iframe>', $bbcode);
        $bbcode = preg_replace('#(^|[ \n\r\t])([a-z0-9]{1,6}://([a-z0-9\-]{1,}(\.?)){1,}[a-z]{2,5}(:[0-9]{2,5}){0,1}((\/|~|\#|\?|=|&amp;|&|\+){1}[a-z0-9\-._%]{0,}){0,})#si', '\\1<a target="_blank" href="\\2">\\2</a>', $bbcode);
        $bbcode = preg_replace('#(^|[ \n\r\t])((www\.){1}([a-z0-9\-]{1,}(\.?)){1,}[a-z]{2,5}(:[0-9]{2,5}){0,1}((\/|~|\#|\?|=|&amp;|&|\+){1}[a-z0-9\-._%]{0,}){0,})#si', '\\1<a target="_blank" href="http://\\2">\\2</a>', $bbcode);
        $bbcode = preg_replace('#(^|[ \n\r\t])(([a-z0-9\-_]{1,}(\.?)){1,}@([a-z0-9\-]{1,}(\.?)){1,}[a-z]{2,5})#si', '\\1<a href="mailto:\\2">\\2</a>', $bbcode);
        $bbcode = preg_replace("_\[big](.*)\[/big\]_si", '<span style="font-size:16px">$1</span>', $bbcode);
        $bbcode = preg_replace("_\[small](.*)\[/small\]_si", '<small>$1</small>', $bbcode);

        return $bbcode;
    }


    public static function tagURL($string)
    {

        $urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";

        if (stristr($urlregex, $string)) {

            return '<a href="' . $string . '" target="_blank">' . $string . '</a>';

        }

        return $string;

    }
}