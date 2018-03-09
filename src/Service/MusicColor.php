<?php

namespace App\Service;

class MusicColor extends \Twig_Extension {

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('mcolor', [$this, 'musicColor']),
            new \Twig_SimpleFilter('mcolorclass', [$this, 'musicColorClass']),
            new \Twig_SimpleFilter('mtitle', [$this, 'musicTitle'])
        ];
    }

    public function musicColor($style) {
        switch ($style) {
            case 'G':
                $style = '#ff4136';
                break;
            case 'T':
                $style = '#ffbe09';
                break;
            case 'E':
                $style = '#0074d9';
                break;
            case 'D':
                $style = '#2ecc40';
                break;
            case 'S':
                $style = '#39cccc';
        }
        return $style;
    }

    public function musicColorClass($style) {
        switch ($style) {
            case 'G':
                $style = 'red';
                break;
            case 'T':
                $style = 'yellow';
                break;
            case 'E':
                $style = 'blue';
                break;
            case 'D':
                $style = 'green';
                break;
            case 'S':
                $style = 'cyan';
        }
        return $style;
    }

    public function musicTitle($style) {
        switch ($style) {
            case 'G':
                $style = 'Games';
                break;
            case 'T':
                $style = 'Trap';
                break;
            case 'E':
                $style = 'Electro & House';
                break;
            case 'D':
                $style = 'Dubstep & DnB';
                break;
            case 'S':
                $style = 'Search';
        }
        return $style;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'music_color';
    }

}