<?php

namespace BananaStation\MusicBundle\Service;

class MusicColor extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('mcolor', array($this, 'musicColor')),
        );
    }

    public function musicColor($style)
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