<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\Content;

use Symfony\Component\String\Inflector\EnglishInflector;
use function Symfony\Component\String\u;

final class ContentTypeLabels
{
    private ?string $singularName = null;
    private ?string $pluralName = null;
    private ?string $menuName = null;
    private ?string $addNew = null;
    private ?string $addNewItem = null;
    private ?string $editItem = null;
    private ?string $newItem = null;
    private ?string $viewItem = null;
    private ?string $viewItems = null;
    private ?string $searchItems = null;
    private ?string $notFound = null;
    private ?string $notFoundInTrash = null;
    private ?string $parentItem = null;
    private ?string $allItems = null;
    private ?string $archives = null;

    /**
     * ContentTypeLabels constructor.
     */
    public function __construct(string $singularName)
    {
        $inflector = new EnglishInflector();
        $singularName = u($singularName)->replace('_', ' ')->lower()->toString();
        $pluralName = (string) current($inflector->pluralize($singularName));

        $this
            ->setSingularName($singularName)
            ->setMenuName(u($pluralName)->title())
            ->setPluralName($pluralName)
            ->setAddNew('Add new')
            ->setAddNewItem('Add new ' . $singularName)
            ->setEditItem('Edit ' . $singularName)
            ->setNewItem('New ' . $singularName)
            ->setViewItem('View ' . $singularName)
            ->setViewItems('View ' . $pluralName)
            ->setSearchItems('Search ' . $pluralName)
            ->setNotFound("No {$pluralName} found")
            ->setNotFoundInTrash("No {$pluralName} found in trash")
            ->setAllItems('All ' . $pluralName)
            ->setArchives(u($pluralName)->title() . ' archives')
        ;
    }

    public function getSingularName(): ?string
    {
        return $this->singularName;
    }

    public function setSingularName(?string $singularName): self
    {
        $this->singularName = $singularName;

        return $this;
    }

    public function getPluralName(): ?string
    {
        return $this->pluralName;
    }

    public function setPluralName(?string $pluralName): self
    {
        $this->pluralName = $pluralName;

        return $this;
    }

    public function getMenuName(): ?string
    {
        return $this->menuName;
    }

    public function setMenuName(?string $menuName): self
    {
        $this->menuName = $menuName;

        return $this;
    }

    public function getAddNew(): ?string
    {
        return $this->addNew;
    }

    public function setAddNew(?string $addNew): self
    {
        $this->addNew = $addNew;

        return $this;
    }

    public function getAddNewItem(): ?string
    {
        return $this->addNewItem;
    }

    public function setAddNewItem(?string $addNewItem): self
    {
        $this->addNewItem = $addNewItem;

        return $this;
    }

    public function getEditItem(): ?string
    {
        return $this->editItem;
    }

    public function setEditItem(?string $editItem): self
    {
        $this->editItem = $editItem;

        return $this;
    }

    public function getNewItem(): ?string
    {
        return $this->newItem;
    }

    public function setNewItem(?string $newItem): self
    {
        $this->newItem = $newItem;

        return $this;
    }

    public function getViewItem(): ?string
    {
        return $this->viewItem;
    }

    public function setViewItem(?string $viewItem): self
    {
        $this->viewItem = $viewItem;

        return $this;
    }

    public function getViewItems(): ?string
    {
        return $this->viewItems;
    }

    public function setViewItems(?string $viewItems): self
    {
        $this->viewItems = $viewItems;

        return $this;
    }

    public function getSearchItems(): ?string
    {
        return $this->searchItems;
    }

    public function setSearchItems(?string $searchItems): self
    {
        $this->searchItems = $searchItems;

        return $this;
    }

    public function getNotFound(): ?string
    {
        return $this->notFound;
    }

    public function setNotFound(?string $notFound): self
    {
        $this->notFound = $notFound;

        return $this;
    }

    public function getNotFoundInTrash(): ?string
    {
        return $this->notFoundInTrash;
    }

    public function setNotFoundInTrash(?string $notFoundInTrash): self
    {
        $this->notFoundInTrash = $notFoundInTrash;

        return $this;
    }

    public function getParentItem(): ?string
    {
        return $this->parentItem;
    }

    public function setParentItem(?string $parentItem): self
    {
        $this->parentItem = $parentItem;

        return $this;
    }

    public function getAllItems(): ?string
    {
        return $this->allItems;
    }

    public function setAllItems(?string $allItems): self
    {
        $this->allItems = $allItems;

        return $this;
    }

    public function getArchives(): ?string
    {
        return $this->archives;
    }

    public function setArchives(?string $archives): self
    {
        $this->archives = $archives;

        return $this;
    }
}
