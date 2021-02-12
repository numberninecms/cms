<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Security;

final class Capabilities
{
    public const READ = 'read';
    public const ACCESS_ADMIN = 'access_admin';

    public const READ_POSTS = 'read_posts';
    public const READ_PRIVATE_POSTS = 'read_private_posts';
    public const EDIT_POSTS = 'edit_posts';
    public const EDIT_PRIVATE_POSTS = 'edit_private_posts';
    public const EDIT_OTHERS_POSTS = 'edit_others_posts';
    public const EDIT_PUBLISHED_POSTS = 'edit_published_posts';
    public const PUBLISH_POSTS = 'publish_posts';
    public const DELETE_POSTS = 'delete_posts';
    public const DELETE_PRIVATE_POSTS = 'delete_private_posts';
    public const DELETE_OTHERS_POSTS = 'delete_others_posts';
    public const DELETE_PUBLISHED_POSTS = 'delete_published_posts';

    public const MANAGE_CATEGORIES = 'manage_categories';
    public const MODERATE_COMMENTS = 'moderate_comments';
    public const UPLOAD_FILES = 'upload_files';
    public const MANAGE_OPTIONS = 'manage_options';
    public const LIST_USERS = 'list_users';
    public const PROMOTE_USERS = 'promote_users';
    public const REMOVE_USERS = 'remove_users';
    public const EDIT_USERS = 'edit_users';
    public const ADD_USERS = 'add_users';
    public const CREATE_USERS = 'create_users';
    public const DELETE_USERS = 'delete_users';
    public const MANAGE_ROLES = 'manage_roles';
    public const CUSTOMIZE = 'customize';
}
