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
     * @param string $singularName
     */
    public function __construct(string $singularName)
    {
        $inflector = new EnglishInflector();
        $singularName = u($singularName)->replace('_', ' ')->lower()->toString();
        $pluralName = current($inflector->pluralize($singularName));

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
            ->setNotFound("No $pluralName found")
            ->setNotFoundInTrash("No $pluralName found in trash")
            ->setAllItems('All ' . $pluralName)
            ->setArchives($pluralName . ' archives');
    }

    /**
     * @return string|null
     */
    public function getSingularName(): ?string
    {
        return $this->singularName;
    }

    /**
     * @param string|null $singularName
     * @return ContentTypeLabels
     */
    public function setSingularName(?string $singularName): ContentTypeLabels
    {
        $this->singularName = $singularName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPluralName(): ?string
    {
        return $this->pluralName;
    }

    /**
     * @param string|null $pluralName
     * @return ContentTypeLabels
     */
    public function setPluralName(?string $pluralName): ContentTypeLabels
    {
        $this->pluralName = $pluralName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMenuName(): ?string
    {
        return $this->menuName;
    }

    /**
     * @param string|null $menuName
     * @return ContentTypeLabels
     */
    public function setMenuName(?string $menuName): ContentTypeLabels
    {
        $this->menuName = $menuName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddNew(): ?string
    {
        return $this->addNew;
    }

    /**
     * @param string|null $addNew
     * @return ContentTypeLabels
     */
    public function setAddNew(?string $addNew): ContentTypeLabels
    {
        $this->addNew = $addNew;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddNewItem(): ?string
    {
        return $this->addNewItem;
    }

    /**
     * @param string|null $addNewItem
     * @return ContentTypeLabels
     */
    public function setAddNewItem(?string $addNewItem): ContentTypeLabels
    {
        $this->addNewItem = $addNewItem;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEditItem(): ?string
    {
        return $this->editItem;
    }

    /**
     * @param string|null $editItem
     * @return ContentTypeLabels
     */
    public function setEditItem(?string $editItem): ContentTypeLabels
    {
        $this->editItem = $editItem;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNewItem(): ?string
    {
        return $this->newItem;
    }

    /**
     * @param string|null $newItem
     * @return ContentTypeLabels
     */
    public function setNewItem(?string $newItem): ContentTypeLabels
    {
        $this->newItem = $newItem;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getViewItem(): ?string
    {
        return $this->viewItem;
    }

    /**
     * @param string|null $viewItem
     * @return ContentTypeLabels
     */
    public function setViewItem(?string $viewItem): ContentTypeLabels
    {
        $this->viewItem = $viewItem;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getViewItems(): ?string
    {
        return $this->viewItems;
    }

    /**
     * @param string|null $viewItems
     * @return ContentTypeLabels
     */
    public function setViewItems(?string $viewItems): ContentTypeLabels
    {
        $this->viewItems = $viewItems;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSearchItems(): ?string
    {
        return $this->searchItems;
    }

    /**
     * @param string|null $searchItems
     * @return ContentTypeLabels
     */
    public function setSearchItems(?string $searchItems): ContentTypeLabels
    {
        $this->searchItems = $searchItems;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNotFound(): ?string
    {
        return $this->notFound;
    }

    /**
     * @param string|null $notFound
     * @return ContentTypeLabels
     */
    public function setNotFound(?string $notFound): ContentTypeLabels
    {
        $this->notFound = $notFound;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNotFoundInTrash(): ?string
    {
        return $this->notFoundInTrash;
    }

    /**
     * @param string|null $notFoundInTrash
     * @return ContentTypeLabels
     */
    public function setNotFoundInTrash(?string $notFoundInTrash): ContentTypeLabels
    {
        $this->notFoundInTrash = $notFoundInTrash;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getParentItem(): ?string
    {
        return $this->parentItem;
    }

    /**
     * @param string|null $parentItem
     * @return ContentTypeLabels
     */
    public function setParentItem(?string $parentItem): ContentTypeLabels
    {
        $this->parentItem = $parentItem;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAllItems(): ?string
    {
        return $this->allItems;
    }

    /**
     * @param string|null $allItems
     * @return ContentTypeLabels
     */
    public function setAllItems(?string $allItems): ContentTypeLabels
    {
        $this->allItems = $allItems;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getArchives(): ?string
    {
        return $this->archives;
    }

    /**
     * @param string|null $archives
     * @return ContentTypeLabels
     */
    public function setArchives(?string $archives): ContentTypeLabels
    {
        $this->archives = $archives;
        return $this;
    }
}
