/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { RawEditorSettings } from 'tinymce';

interface Options {
    target?: HTMLElement;
    selector?: string;
    css?: string | string[];
}

export default function createTinymceOptions(options: Options): RawEditorSettings {
    return {
        target: options.target,
        selector: options.selector,
        theme: 'silver',
        skin: false,
        content_css: options.css ?? false,
        content_style: '#tinymce { padding: 1rem !important; font-size: 1rem; }',
        branding: false,
        min_height: 350,
        relative_urls: false,
        plugins: [
            'advlist autolink lists link image imagetools charmap print preview anchor',
            'searchreplace visualblocks code',
            'insertdatetime media table paste code help wordcount',
            'medialibrary',
            'autoresize',
        ],
        toolbar:
            'medialibrary | undo redo copy paste | link | formatselect | styleselect | \
            bold italic backcolor | alignleft aligncenter alignright alignjustify | \
            bullist numlist outdent indent | charmap anchor | searchreplace | \
            removeformat | code | wordcount | help',
        fontsize_formats:
            'XSmall=0.75rem Small=0.875rem Normal=1rem Large=1.125rem XLarge=1.25rem X2Large=1.5rem ' +
            'X3Large=1.875rem X4Large=2.25rem X5Large=3rem X6Large=3.75rem X7Large=4.5rem X8Large=6rem X9Large=8rem',
        formats: {
            alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'left' },
            aligncenter: {
                selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video',
                classes: 'center',
            },
            alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'right' },
            alignfull: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img,audio,video', classes: 'full' },
            bold: { inline: 'strong' },
            italic: { inline: 'span', classes: 'italic' },
            underline: { inline: 'span', classes: 'underline', exact: true },
            strikethrough: { inline: 'del' },
        },
        style_formats: [
            { title: 'Small text', selector: 'p,ul,ol,span,table,div', classes: 'text-sm' },
            { title: 'Margin bottom', selector: 'p,h1,h2,h3,h4,h5,h6,ul,ol,span,table,div', classes: 'mb-5' },
            { title: 'Unstyled list', selector: 'ul,ol', classes: 'list-none' },
        ],
    };
}
