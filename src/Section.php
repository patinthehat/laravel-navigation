<?php

namespace Spatie\Navigation;

use Spatie\Navigation\Traits\Conditions as ConditionsTrait;

class Section implements Node
{
    use ConditionsTrait;

    public Node $parent;

    public string $url;

    public string $title;

    public bool $visible;

    /** @var Section[] */
    public array $children;

    /** @var string[] */
    public array $attributes;

    public function __construct(Node $parent, string $title = '', string $url = '')
    {
        $this->parent = $parent;

        $this->title = $title;
        $this->url = $url;

        $this->visible = true;
        $this->attributes = [];

        $this->children = [];
    }

    public function add(string $title = '', string $url = '', ?callable $configure = null): self
    {
        $section = new Section($this, $title, $url);

        if ($configure) {
            $configure($section);
        }

        $this->children[] = $section;

        return $this;
    }

    public function addIf($condition, string $title = '', string $url = '', ?callable $configure = null): self
    {
        if ($this->resolveCondition($condition)) {
            $this->add($title, $url, $configure);
        }

        return $this;
    }

    public function attributes(array $attributes): self
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    public function show(bool $visible = true): self
    {
        $this->visible = $visible;

        return $this;
    }

    public function hide(bool $hidden = true): self
    {
        $this->visible = ! $hidden;

        return $this;
    }

    public function getParent(): ?Node
    {
        return $this->parent;
    }

    /** @return Node[] */
    public function getParents(): array
    {
        if (! $this->parent) {
            return [];
        }

        return array_merge($this->parent->getParents(), [$this->parent]);
    }
}
