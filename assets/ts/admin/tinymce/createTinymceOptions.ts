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
        content_style: '#tinymce { padding: 1rem !important; }',
        branding: false,
        min_height: 350,
        menubar: false,
        relative_urls: false,
        plugins: [
            'advlist autolink lists link image imagetools charmap print preview anchor',
            'searchreplace visualblocks code',
            'insertdatetime media table paste code help wordcount',
            'medialibrary',
            'autoresize',
        ],
        toolbar:
            'medialibrary | undo redo copy paste | formatselect | bold italic backcolor | \
            alignleft aligncenter alignright alignjustify | \
            bullist numlist outdent indent | charmap anchor | searchreplace | \
            removeformat | code | wordcount | help',
        formats: {
            alignleft: { selector: 'img', styles: { float: 'left', margin: '10px 10px 10px 0' } },
            alignright: { selector: 'img', styles: { float: 'right', margin: '10px 0 10px 10px' } },
            aligncenter: { selector: 'img', styles: { display: 'block', margin: '10px auto' } },
        },
    };
}
