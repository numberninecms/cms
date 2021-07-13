/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import MediaFileUploadedEvent from 'admin/events/MediaFileUploadedEvent';
import MediaLibraryThumbnailClickedEvent from 'admin/events/MediaLibraryThumbnailClickedEvent';
import { MediaViewerEvent } from 'admin/events/MediaViewerEvent';
import ModalVisibilityChangedEvent from 'admin/events/ModalVisibilityChangedEvent';
import ModalShowEvent from 'admin/events/ModalShowEvent';
import ModalCloseEvent from 'admin/events/ModalCloseEvent';
import PageBuilderCreatedEvent from 'admin/events/PageBuilderCreatedEvent';
import { PageBuilderFrameHeightChangedEvent } from 'admin/events/PageBuilderFrameHeightChangedEvent';
import MouseCoordinatesEvent from 'admin/events/MouseCoordinatesEvent';
import PageBuilderSavePresetEvent from 'admin/events/PageBuilderSavePresetEvent';
import PageBuilderComponentDeletedEvent from 'admin/events/PageBuilderComponentDeletedEvent';
import PageBuilderComponentSelectedEvent from 'admin/events/PageBuilderComponentSelectedEvent';
import PageBuilderComponentUpdatedEvent from 'admin/events/PageBuilderComponentUpdatedEvent';
import PageBuilderComponentsTreeChangedEvent from 'admin/events/PageBuilderComponentsTreeChangedEvent';
import PageBuilderComponentsComponentsLoadedEvent from 'admin/events/PageBuilderComponentsComponentsLoadedEvent';
import PageBuilderRequestForShowShortcodeEvent from 'admin/events/PageBuilderRequestForShowShortcodeEvent';
import PageBuilderRequestForDeleteComponentEvent from 'admin/events/PageBuilderRequestForDeleteComponentEvent';
import PageBuilderRequestForSelectComponentEvent from 'admin/events/PageBuilderRequestForSelectComponentEvent';
import PageBuilderRequestForEditComponentEvent from 'admin/events/PageBuilderRequestForEditComponentEvent';
import PageBuilderRequestForHighlightComponentEvent from 'admin/events/PageBuilderRequestForHighlightComponentEvent';
import PageBuilderRequestForChangeComponentsTreeEvent from 'admin/events/PageBuilderRequestForChangeComponentsTreeEvent';
import PageBuilderRequestForAddToContentEvent from 'admin/events/PageBuilderRequestForAddToContentEvent';
import { PageBuilderRequestForChangeViewportSizeEvent } from 'admin/events/PageBuilderRequestForChangeViewportSizeEvent';
import { SplitterDraggingEvent } from 'admin/events/SplitterDraggingEvent';
import PageBuilderComponentsComponentsSavedEvent from 'admin/events/PageBuilderComponentsComponentsSavedEvent';
import FlashShowEvent from 'admin/events/FlashShowEvent';
import MenuAddItemsEvent from 'admin/events/MenuAddItemsEvent';

export const EVENT_MEDIA_UPLOADER_FILE_UPLOADED = 'media-uploader:file-uploaded';
export const EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_CLICKED = 'media-thumbnails-list:thumbnail-clicked';
export const EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_SHIFT_CLICKED = 'media-thumbnails-list:thumbnail-shift-clicked';
export const EVENT_MEDIA_SELECT = 'media:select';

export type MediaLibraryEvents = {
    [EVENT_MEDIA_UPLOADER_FILE_UPLOADED]: MediaFileUploadedEvent;
    [EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_CLICKED]: MediaLibraryThumbnailClickedEvent;
    [EVENT_MEDIA_THUMBNAILS_LIST_THUMBNAIL_SHIFT_CLICKED]: MediaLibraryThumbnailClickedEvent;
    [EVENT_MEDIA_SELECT]: MediaViewerEvent;
};

export const EVENT_TINY_EDITOR_ADD_MEDIA = 'tiny-editor:add-media';

export type TinyEditorEvents = {
    [EVENT_TINY_EDITOR_ADD_MEDIA]: MediaViewerEvent;
};

export const EVENT_MODAL_VISIBILITY_CHANGED = 'modal:visibility-changed';
export const EVENT_MODAL_SHOW = 'modal:show';
export const EVENT_MODAL_CLOSE = 'modal:close';

export type ModalEvents = {
    [EVENT_MODAL_VISIBILITY_CHANGED]: ModalVisibilityChangedEvent;
    [EVENT_MODAL_SHOW]: ModalShowEvent;
    [EVENT_MODAL_CLOSE]: ModalCloseEvent;
};

export const EVENT_PAGE_BUILDER_CREATED = 'page-builder:created';
export const EVENT_PAGE_BUILDER_LOADED = 'page-builder:loaded';
export const EVENT_PAGE_BUILDER_FRAME_HEIGHT_CHANGED = 'page-builder:frame-height-changed';
export const EVENT_PAGE_BUILDER_MOUSE_COORDINATES_CHANGED = 'page-builder:mouse-coordinates-changed';
export const EVENT_PAGE_BUILDER_SAVE_PRESET = 'page-builder:save-preset';
export const EVENT_PAGE_BUILDER_COMPONENT_DELETED = 'page-builder:component-deleted';
export const EVENT_PAGE_BUILDER_COMPONENT_SELECTED = 'page-builder:component-selected';
export const EVENT_PAGE_BUILDER_COMPONENT_UPDATED = 'page-builder:component-updated';
export const EVENT_PAGE_BUILDER_COMPONENTS_TREE_CHANGED = 'page-builder:components-tree-changed';
export const EVENT_PAGE_BUILDER_COMPONENTS_LOADED = 'page-builder:components-loaded';
export const EVENT_PAGE_BUILDER_COMPONENTS_SAVED = 'page-builder:components-saved';
export const EVENT_PAGE_BUILDER_REQUEST_FOR_SHOW_SHORTCODE = 'page-builder:request-for-show-shortcode';
export const EVENT_PAGE_BUILDER_REQUEST_FOR_DELETE_COMPONENT = 'page-builder:request-for-delete-component';
export const EVENT_PAGE_BUILDER_REQUEST_FOR_SELECT_COMPONENT = 'page-builder:request-for-select-component';
export const EVENT_PAGE_BUILDER_REQUEST_FOR_EDIT_COMPONENT = 'page-builder:request-for-edit-component';
export const EVENT_PAGE_BUILDER_REQUEST_FOR_HIGHLIGHT_COMPONENT = 'page-builder:request-for-highlight-component';
export const EVENT_PAGE_BUILDER_REQUEST_FOR_SHOW_COMPONENTS_TREE = 'page-builder:request-for-show-components-tree';
export const EVENT_PAGE_BUILDER_REQUEST_FOR_SAVE_COMPONENTS_TREE = 'page-builder:request-for-save-components-tree';
export const EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_COMPONENTS_TREE = 'page-builder:request-for-change-components-tree';
export const EVENT_PAGE_BUILDER_REQUEST_FOR_ADD_TO_CONTENT = 'page-builder:request-for-add-to-content';
export const EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_VIEWPORT_SIZE_EVENT =
    'page-builder:request-for-change-viewport-size';

export type PageBuilderEvents = {
    [EVENT_PAGE_BUILDER_CREATED]: PageBuilderCreatedEvent;
    [EVENT_PAGE_BUILDER_LOADED]: void;
    [EVENT_PAGE_BUILDER_FRAME_HEIGHT_CHANGED]: PageBuilderFrameHeightChangedEvent;
    [EVENT_PAGE_BUILDER_MOUSE_COORDINATES_CHANGED]: MouseCoordinatesEvent;
    [EVENT_PAGE_BUILDER_SAVE_PRESET]: PageBuilderSavePresetEvent;
    [EVENT_PAGE_BUILDER_COMPONENT_DELETED]: PageBuilderComponentDeletedEvent;
    [EVENT_PAGE_BUILDER_COMPONENT_SELECTED]: PageBuilderComponentSelectedEvent;
    [EVENT_PAGE_BUILDER_COMPONENT_UPDATED]: PageBuilderComponentUpdatedEvent;
    [EVENT_PAGE_BUILDER_COMPONENTS_TREE_CHANGED]: PageBuilderComponentsTreeChangedEvent;
    [EVENT_PAGE_BUILDER_COMPONENTS_LOADED]: PageBuilderComponentsComponentsLoadedEvent;
    [EVENT_PAGE_BUILDER_COMPONENTS_SAVED]: PageBuilderComponentsComponentsSavedEvent;
    [EVENT_PAGE_BUILDER_REQUEST_FOR_SHOW_SHORTCODE]: PageBuilderRequestForShowShortcodeEvent;
    [EVENT_PAGE_BUILDER_REQUEST_FOR_DELETE_COMPONENT]: PageBuilderRequestForDeleteComponentEvent;
    [EVENT_PAGE_BUILDER_REQUEST_FOR_SELECT_COMPONENT]: PageBuilderRequestForSelectComponentEvent;
    [EVENT_PAGE_BUILDER_REQUEST_FOR_EDIT_COMPONENT]: PageBuilderRequestForEditComponentEvent;
    [EVENT_PAGE_BUILDER_REQUEST_FOR_HIGHLIGHT_COMPONENT]: PageBuilderRequestForHighlightComponentEvent;
    [EVENT_PAGE_BUILDER_REQUEST_FOR_SHOW_COMPONENTS_TREE]: void;
    [EVENT_PAGE_BUILDER_REQUEST_FOR_SAVE_COMPONENTS_TREE]: void;
    [EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_COMPONENTS_TREE]: PageBuilderRequestForChangeComponentsTreeEvent;
    [EVENT_PAGE_BUILDER_REQUEST_FOR_ADD_TO_CONTENT]: PageBuilderRequestForAddToContentEvent;
    [EVENT_PAGE_BUILDER_REQUEST_FOR_CHANGE_VIEWPORT_SIZE_EVENT]: PageBuilderRequestForChangeViewportSizeEvent;
};

export const EVENT_SLIDABLE_INPUT_POSITION = 'slidable-input:position';
export const EVENT_SLIDABLE_INPUT_START_DRAGGING = 'slidable-input:start-dragging';
export const EVENT_SLIDABLE_INPUT_STOP_DRAGGING = 'slidable-input:stop-dragging';

export type FormComponentEvents = {
    [EVENT_SLIDABLE_INPUT_POSITION]: MouseCoordinatesEvent;
    [EVENT_SLIDABLE_INPUT_START_DRAGGING]: string;
    [EVENT_SLIDABLE_INPUT_STOP_DRAGGING]: string;
};

export const EVENT_SPLITTER_DRAGGING = 'splitter:dragging';

export type SplitterEvents = {
    [EVENT_SPLITTER_DRAGGING]: SplitterDraggingEvent;
};

export const EVENT_FLASH_SHOW = 'flash:show';

export type FlashEvents = {
    [EVENT_FLASH_SHOW]: FlashShowEvent;
};

export const EVENT_MENU_ADD_ITEMS = 'menu:add-items';

export type MenuEvents = {
    [EVENT_MENU_ADD_ITEMS]: MenuAddItemsEvent;
};

export type Events = MediaLibraryEvents &
    TinyEditorEvents &
    ModalEvents &
    PageBuilderEvents &
    FormComponentEvents &
    SplitterEvents &
    FlashEvents &
    MenuEvents;
