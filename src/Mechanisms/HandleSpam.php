<?php

namespace Signalmetrics\Signal\Mechanisms;

class HandleSpam {


    /**
     * @todo figure out what to do with spam.
     * SHould we notify?
     */
    public function handle(string $ip)
    {
        info("Spam detected – {$ip}");
    }

}