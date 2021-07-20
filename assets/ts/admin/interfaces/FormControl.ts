/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import FormControlParameters from 'admin/interfaces/FormControlParameters';

export default interface FormControl {
    name: string;
    parameters: FormControlParameters;
    customParameters?: FormControlParameters;
}
