<?php

class Paginace
{

    public function urlStrany(string $url, int $strana)
    {
        return str_replace('{strana}', $strana, $url);
    }

    public function paginace(int $strana, int $stran, string $url)
    {
        $polomer = 3; // Poloměr oblasti kolem aktuální stránky
        $html = '<nav class="centrovany"><ul class="paginace">';
        // Šipka vlevo
        if ($strana > 1) {
            $html .= '<li><a href="' . $this->urlStrany($url, $strana - 1) . '">&laquo;</a></li>';
        } else {
            $html .= '<li class="neaktivni">&laquo;</li>';
        }
        $left = $strana - $polomer >= 1 ? $strana - $polomer : 1;
        $right = $strana + $polomer <= $stran ? $strana + $polomer : $stran;
        // Umístění jedničky
        if ($left > 1) {
            $html .= '<li><a href="' . $this->urlStrany($url, 1) . '">1</a></li>';
        }
        // Tečky vlevo
        if ($left > 2) {
            $html .= '<li class="neaktivni">&hellip;</li>';
        }
        // Stránky v radiusu
        for ($i = $left; $i <= $right; $i++) {
            if ($i == $strana) { // Aktivní stránka
                $html .= '<li class="aktivni">' . $i . '</li>';
            } else {
                $html .= '<li><a href="' . $this->urlStrany($url, $i) . '">' . $i . '</a></li>';
            }
        }
        // Tečky vpravo
        if ($right < $stran - 1) {
            $html .= '<li class="neaktivni">' . '&hellip;' . '</li>';
        }
        // Umístění poslední stránky
        if ($right < $stran) {
            $html .= '<li><a href="' . $this->urlStrany($url, $stran) . '">' . $stran . '</a></li>';
        }
        // Šipka vpravo
        if ($strana < $stran) {
            $html .= '<li><a href="' . $this->urlStrany($url, $strana + 1) . '">&raquo;</a></li>';
        } else {
            $html .= '<li class="neaktivni">&laquo;</li>';
        }
        $html .= '</ul></nav>';
        return $html;
    }
}
