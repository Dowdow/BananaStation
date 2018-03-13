<?php

namespace App\Service;

use Twig_SimpleFilter;

class ProjectState extends \Twig_Extension
{
    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters(): array
    {
        return [
            new Twig_SimpleFilter('pstate', [$this, 'stateFilter']),
        ];
    }

    /**
     * @param $state
     * @return string
     */
    public function stateFilter($state): string
    {
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
    public function getName(): string
    {
        return 'project_state';
    }
}