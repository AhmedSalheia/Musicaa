<?php


namespace MUSICAA\lib\traits;

use Firebase\JWT\JWT;
use MUSICAA\models\TokenMod;
use MUSICAA\models\Tracker;
use MUSICAA\models\TrackUserData;

trait Helper
{
    use InputFilter;
    use Encription;
    use Mailing;
    use Auth;
    use HttpThings;
    use ChannelThings;
    use VideoRelated;

    public function redirect($page){
        session_write_close();
        header('Location: '.$page);
        exit();
    }

    public function fromBin($int)
    {
        $count = 0;
        $decr = 0;
        while($int !== 0)
        {
            $num = $int%10;
            $int = (int)floor($int / 10);

            $decr += $num * 2**$count++;
        }

        return $decr;
    }

    public function toBin($int)
    {
        $decr = 0;
        while($int !== 0)
        {
            $num = $int%2;
            $int = (int)floor($int / 2);

            $decr = ($decr*10) + $num;
        }

        return $decr.'';
    }

    public function randText($num)
    {
        return str_split(str_shuffle('absdefghijklmnopqrstuvwxyz1234567890'),$num)[0];
    }

    public static function randName($num)
    {
        return str_split(str_shuffle('absdefghijklmnopqrstuvwxyz1234567890'),$num)[0];
    }

    public function track($userId,$action,$at)
    {
        $tracker = new Tracker();
        $tracker->userId = $userId;
        $tracker->action = $action;
        $tracker->at = $at;
        $tracker->save();
    }

    public function trackUserData($col,$userId,$from,$to)
    {
        $tracker = new Tracker();
        $tracker->userId = $userId;
        $tracker->action = 'change';
        $tracker->at = 'user.'.$col;
        $tracker->user_rel = 'y';
        $tracker->save();

        $trackUser = new TrackUserData();
        $trackUser->trackId = $tracker->id;
        $trackUser->changedFrom = $from;
        $trackUser->changedTo= $to;
        $trackUser->save();
    }
}
