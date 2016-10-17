<?php

namespace BananaStation\MusicBundle\Service;

class MusicColor extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('mcolor', array($this, 'musicColor')),
            new \Twig_SimpleFilter('mcolorclass', array($this, 'musicColorClass')),
        );
    }

    public function musicColor($style)
    {
        switch ($style) {
            case 'G':
                $style = '#ff4136';
                break;
            case 'H':
                $style = '#ff851b';
                break;
            case 'M':
                $style = '#ffdc00';
                break;
            case 'E':
                $style = '#0074d9';
                break;
            case 'D':
                $style = '#2ecc40';
                break;
            case 'R':
                $style = '#aaaaaa';
                break;
            case 'S':
                $style = '#39cccc';
        }
        return $style;
    }

    public function musicColorClass($style)
    {
        switch ($style) {
            case 'G':
                $style = 'red';
                break;
            case 'H':
                $style = 'orange';
                break;
            case 'M':
                $style = 'yellow';
                break;
            case 'E':
                $style = 'blue';
                break;
            case 'D':
                $style = 'green';
                break;
            case 'R':
                $style = 'grey';
                break;
            case 'S':
                $style = 'cyan';
        }
        return $style;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'music_color';
    }

}