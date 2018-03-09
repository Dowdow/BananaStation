<?php

namespace App\Service;

class ProjectState extends \Twig_Extension {

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('pstate', [$this, 'stateFilter']),
        ];
    }

    public function stateFilter($state) {
        switch ($state) {
            case 'E':
                $state = 'En cours';
                break;
            case 'T':
                $state = 'Terminé';
                break;
            case 'P':
                $state = 'En pause';
                break;
        }
        return $state;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'project_state';
    }
}