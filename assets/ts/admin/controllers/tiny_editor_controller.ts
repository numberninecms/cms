/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';
import tinymce from 'tinymce';
import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/skins/ui/oxide/skin.css';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autoresize';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/imagetools';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/print';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/media';
import 'tinymce/plugins/table';
import 'tinymce/plugins/paste';
import 'tinymce/plugins/help';
import 'tinymce/plugins/wordcount';
import 'admin/tinymce/plugins/medialibrary';

export default class extends Controller {
    public static values = {
        css: String,
    };

    public static targets = ['editor'];

    private readonly cssValue: string;
    private readonly editorTarget: HTMLTextAreaElement;

    public connect(): void {
        void tinymce.init({
            target: this.editorTarget,
            theme: 'silver',
            skin: false,
            content_css: this.cssValue ?? false,
            content_style: '#tinymce { padding: 1rem !important; }',
            branding: false,
            height: 500,
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
        });
    }

    public disconnect(): void {
        if (tinymce.editors.length > 0) {
            tinymce.editors.forEach((editor) => {
                editor.remove();
            });
        }
    }
}
