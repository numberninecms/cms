/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from '@hotwired/stimulus';
import checkAndSubmit from 'admin/functions/checkAndSubmit';

export default class extends Controller {
    private readonly formName = 'admin_comment_index_form';
    private readonly formCheckboxPrefix = 'comment';

    public deleteComment(event: MouseEvent): void {
        event.preventDefault();

        checkAndSubmit({
            event,
            formName: this.formName,
            formCheckboxPrefix: this.formCheckboxPrefix,
            button: 'delete',
            confirm: {
                icon: 'warning',
            },
        });
    }

    public restoreComment(event: MouseEvent): void {
        event.preventDefault();

        checkAndSubmit({
            event,
            formName: this.formName,
            formCheckboxPrefix: this.formCheckboxPrefix,
            button: 'restore',
            confirm: true,
        });
    }

    public approveComment(event: MouseEvent): void {
        event.preventDefault();

        checkAndSubmit({
            event,
            formName: this.formName,
            formCheckboxPrefix: this.formCheckboxPrefix,
            button: 'approve',
        });
    }

    public unapproveComment(event: MouseEvent): void {
        event.preventDefault();

        checkAndSubmit({
            event,
            formName: this.formName,
            formCheckboxPrefix: this.formCheckboxPrefix,
            button: 'unapprove',
        });
    }
}
