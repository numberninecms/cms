<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Model\General;

interface Settings
{
    public const PAGE_FOR_FRONT = 'page_for_front';
    public const PAGE_FOR_POSTS = 'page_for_posts';
    public const PAGE_FOR_MY_ACCOUNT = 'page_for_my_account';
    public const PAGE_FOR_PRIVACY = 'page_for_privacy';
    public const SITE_TITLE = 'site_title';
    public const SITE_DESCRIPTION = 'site_description';
    public const ROOT_ABSOLUTE_URL = 'root_absolute_url';
    public const POSTS_PER_PAGE = 'posts_per_page';
    public const ACTIVE_THEME = 'active_theme';
    public const PERMALINKS = 'permalinks';
    public const CUSTOMIZER_DRAFT_SETTINGS = 'customizer_draft_settings';
    public const MAILER_SENDER_ADDRESS = 'mailer_sender_address';
    public const MAILER_SENDER_NAME = 'mailer_sender_name';
}
