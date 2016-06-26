<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Emojione\Client;
use Emojione\Emojione;
use Emojione\Ruleset;

/**
 * Ubb component
 */
class UbbComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

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
        $string = self::usernameTags($string);

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
        $bbcode = preg_replace("_\[u\](.*?)\[/u\]_si", '<u>$1</u>', $bbcode);
        $bbcode = preg_replace("_\[s\](.*?)\[/s\]_si", '<strike>$1</strike>', $bbcode);
        $bbcode = preg_replace('#(^|[ \n\r\t])([a-z0-9]{1,6}://([a-z0-9\-]{1,}(\.?)){1,}[a-z]{2,5}(:[0-9]{2,5}){0,1}((\/|~|\#|\?|=|&amp;|&|\+){1}[a-z0-9\-._%]{0,}){0,})#si', '\\1<a target="_blank" href="\\2">\\2</a>', $bbcode);
        $bbcode = preg_replace('#(^|[ \n\r\t])((www\.){1}([a-z0-9\-]{1,}(\.?)){1,}[a-z]{2,5}(:[0-9]{2,5}){0,1}((\/|~|\#|\?|=|&amp;|&|\+){1}[a-z0-9\-._%]{0,}){0,})#si', '\\1<a target="_blank" href="http://\\2">\\2</a>', $bbcode);
        $bbcode = preg_replace('#(^|[ \n\r\t])(([a-z0-9\-_]{1,}(\.?)){1,}@([a-z0-9\-]{1,}(\.?)){1,}[a-z]{2,5})#si', '\\1<a href="mailto:\\2">\\2</a>', $bbcode);

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

    public static function usernameTags($string) {

        $userRegistry = TableRegistry::get('Users');

        $userNames = [];
        $lengthRange = 3 . ',' . 30;

        $usernamePattern = '/(?:^|[^\w])@(?:(("|\'|`)[^\n<>,;&\\\]{' . $lengthRange . '}?\2)|([^\n<>,;&\\\"\'`\.:\-+=~@#$%^*!?()\[\]{}\s]{' . $lengthRange . '}))/u';

        preg_match_all($usernamePattern, $string, $matches);

        foreach($matches as $match) {
            $usernames = str_ireplace('@', '', $match);

            foreach($usernames as $username) {
                $currentUser = $userRegistry->findByUsername($username)->contain(['PrimaryRole'])->first();
                if(!is_null($currentUser)) {
                    $userNames[$username] = '<span data-id="'. $currentUser->id .'" class="mention role ' . $currentUser->primary_role->name . '">' . $currentUser->username . '</span>';
                    $string = str_ireplace($username, $userNames[$username], $string);
                }
            }
        }

        return $string;

    }
}
