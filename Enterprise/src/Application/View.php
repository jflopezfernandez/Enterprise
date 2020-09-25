<?php

declare(strict_types=1);

namespace Enterprise\Application;

class View implements IRenderable
{
    protected string $title;

    protected array $sections = [ ];

    public function __construct(string $title = '')
    {
        $this->title = $title;
    }

    public function setTitle(string $title) : void
    {
        $this->title = $title;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function prependSection(Section $section) : void
    {
        array_unshift($this->sections, $section);
    }

    public function appendSection(Section $section) : void
    {
        array_push($this->sections, $section);
    }

    public function getSections() : array
    {
        return $this->sections;
    }

    public function render() : void
    {
        echo "testing...";
    }
}
