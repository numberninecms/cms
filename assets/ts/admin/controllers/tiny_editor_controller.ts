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
import createTinymceOptions from 'admin/tinymce/createTinymceOptions';

export default class extends Controller {
    public static values = {
        css: String,
    };

    public static targets = ['editor'];

    private readonly cssValue: string;
    private readonly editorTarget: HTMLTextAreaElement;

    public connect(): void {
        void tinymce.init(
            createTinymceOptions({
                target: this.editorTarget,
                css: this.cssValue,
            }),
        );
    }

    public disconnect(): void {
        if (tinymce.editors.length > 0) {
            tinymce.editors.forEach((editor) => {
                editor.remove();
            });
        }
    }
}
