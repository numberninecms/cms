/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import tinymce from 'tinymce/tinymce';
import { eventBus } from 'admin/admin';
import MediaFile from 'admin/interfaces/MediaFile';
import MediaSettings from 'admin/interfaces/MediaSettings';
import { dirname } from 'path';
import { EVENT_MODAL_SHOW, EVENT_TINY_EDITOR_ADD_MEDIA } from 'admin/events/events';

tinymce.PluginManager.add('medialibrary', function (editor) {
    editor.addCommand('n9ShowMediaLibrary', () => {
        eventBus.emit(EVENT_MODAL_SHOW, { modalId: 'media_library' });
        eventBus.emit(EVENT_TINY_EDITOR_ADD_MEDIA, ({ files, settings }) => {
            editor.execCommand('n9InsertFiles', false, { files, settings });
        });
    });

    editor.addCommand('n9InsertFiles', (ui, payload: { files: MediaFile[]; settings: MediaSettings }) => {
        if (payload.files.length > 0) {
            for (const file of payload.files) {
                let mediaTag = '';

                if (file.mimeType.substr(0, 5) === 'image') {
                    const filename =
                        payload.settings.size === 'original'
                            ? file.path
                            : `${dirname(file.path)}/${file.sizes[payload.settings.size].filename}`;
                    mediaTag = `<img src="${filename}" alt="${file.title}">`;
                } else if (file.mimeType.substr(0, 5) === 'video') {
                    mediaTag = `
                        <video controls>
                            <source src="${file.path}" type="${file.mimeType}">
                            <p>Your browser is too old to read HTML5 videos. Please upgrade.</p>
                        </video>
                    `;
                }

                editor.selection.setContent(mediaTag.trim(), { format: `align${payload.settings.alignment}` });
                editor.formatter.apply(`align${payload.settings.alignment}`);
            }
        }
    });

    editor.ui.registry.addButton('medialibrary', {
        icon: 'image',
        text: 'Add media',
        onAction: function () {
            editor.execCommand('n9ShowMediaLibrary');
        },
    });

    return {};
});
