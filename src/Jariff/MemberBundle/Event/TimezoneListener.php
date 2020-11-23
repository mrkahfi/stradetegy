<?php

namespace Jariff\MemberBundle\Event;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class TimezoneListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        // menggenerate partial html content ikut mentrigger event ini juga,
        // di filter disini

        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }
        $request = $event->getRequest();
        $session = $request->getSession();

        $session->set('timezone', 'US/Eastern');
        return;
        $request = $event->getRequest();
        $session = $request->getSession();

        // get timezone jika belum ada

        if (!$session->get('timezone')) {
            $ip = $request->getClientIp();
            $url = 'http://smart-ip.net/geoip-json/' . $ip;

            $options = array('http' => array(
                'user_agent' => 'Firefox wannabe',
                'max_redirects' => 1,
                'timeout' => 10,
            ));
            $context = stream_context_create($options);

            $json = file_get_contents($url, false, $context);

            $ipData = json_decode($json, true);

            if (!empty($ipData['timezone'])) {
                $session->set('timezone', $ipData['timezone']);
//                $tz = new \DateTimeZone( $ip['timezone']);
//                $now = new \DateTime( 'now', $tz); // DateTime object corellated to user's timezone
            } else {
                // we can't determine a timezone - do something else...
                $session->set('timezone', 'US/Eastern');
            }

        }


    }
}