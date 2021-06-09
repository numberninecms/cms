/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import { Controller } from 'stimulus';

export default class extends Controller {
    public static targets = ['username', 'firstName', 'lastName', 'displayNameFormat'];

    private readonly usernameTarget: HTMLInputElement;
    private readonly firstNameTarget: HTMLInputElement;
    private readonly lastNameTarget: HTMLInputElement;
    private readonly displayNameFormatTarget: HTMLSelectElement;

    public updateUsername(): void {
        this.getOptionByValue('username')!.text = this.usernameTarget.value;
    }

    public updateFirstName(): void {
        const firstName = this.firstNameTarget.value.trim().length > 0 ? this.firstNameTarget.value : 'First name';
        const lastName = this.lastNameTarget.value.trim().length > 0 ? this.lastNameTarget.value : 'Last name';
        this.getOptionByValue('first_only')!.text = firstName;
        this.getOptionByValue('first_last')!.text = `${firstName} ${lastName}`;
        this.getOptionByValue('last_first')!.text = `${lastName} ${firstName}`;
    }

    public updateLastName(): void {
        const firstName = this.firstNameTarget.value.trim().length > 0 ? this.firstNameTarget.value : 'First name';
        const lastName = this.lastNameTarget.value.trim().length > 0 ? this.lastNameTarget.value : 'Last name';
        this.getOptionByValue('last_only')!.text = lastName;
        this.getOptionByValue('first_last')!.text = `${firstName} ${lastName}`;
        this.getOptionByValue('last_first')!.text = `${lastName} ${firstName}`;
    }

    private getOptionByValue(value: string): HTMLOptionElement | null {
        for (let i = 0; i < this.displayNameFormatTarget.options.length; i++) {
            if (this.displayNameFormatTarget.options[i].getAttribute('value') === value) {
                return this.displayNameFormatTarget.options[i];
            }
        }

        return null;
    }
}
