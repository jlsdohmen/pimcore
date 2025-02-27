<?php
declare(strict_types=1);

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Event;

final class AdminEvents
{
    /**
     * The LOGIN_REDIRECT event is triggered before user is redirected to login page.
     *
     * This event allows you to influence path of the login page, e.g. for SSO integrations.
     *
     * @Event("Pimcore\Event\Admin\Login\LoginRedirectEvent")
     *
     * @var string
     */
    const LOGIN_REDIRECT = 'pimcore.admin.login.redirect';

    /**
     * The LOGIN_CREDENTIALS event is triggered after login credentials were resolved from request.
     *
     * This event allows you to influence the credentials resolved in the authenticator before
     * they are passed to getUser().
     *
     * @Event("Pimcore\Event\Admin\Login\LoginCredentialsEvent")
     *
     * @var string
     */
    const LOGIN_CREDENTIALS = 'pimcore.admin.login.credentials';

    /**
     * The LOGIN_FAILED event is triggered when credentials were invalid.
     *
     * This event allows you to set a custom user which is resolved from the given credentials
     * from a third-party authentication system (e.g. an external service).
     *
     * @Event("Pimcore\Event\Admin\Login\LoginFailedEvent")
     *
     * @var string
     */
    const LOGIN_FAILED = 'pimcore.admin.login.failed';

    /**
     * The LOGIN_LOSTPASSWORD event is triggered before the lost password email
     * is sent.
     *
     * This event allows you to alter the lost password mail or to prevent
     * mail sending at all. For full control, it allows you to set the response
     * to be returned.
     *
     * @Event("Pimcore\Event\Admin\Login\LostPasswordEvent")
     *
     * @var string
     */
    const LOGIN_LOSTPASSWORD = 'pimcore.admin.login.lostpassword';

    /**
     * The LOGIN_LOGOUT event is triggered before the user is logged out.
     *
     * By setting a response on the event, you're able to control the response
     * returned after logout.
     *
     * @Event("Pimcore\Event\Admin\Login\LogoutEvent")
     *
     * @var string
     */
    const LOGIN_LOGOUT = 'pimcore.admin.login.logout';

    /**
     * The LOGIN_BEFORE_RENDER event is triggered before the login view is rendered.
     *
     * Allows overriding the parameters and including templates.
     * ```php
     * public function getContent(GenericEvent $event): void
     * {
     *     $parameters = $event->getArgument('parameters');
     *     $parameters['includeTemplates']['VendorBundleName'] = '@VendorBundleName/path/to/template.html.twig';
     *     $event->setArgument('parameters', $parameters);
     * }
     * ```
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const LOGIN_BEFORE_RENDER = 'pimcore.admin.login.beforeRender';

    /**
     * The INDEX_SETTINGS event is triggered when the settings object is built for the index page.
     *
     * @Event("Pimcore\Event\Admin\IndexActionSettingsEvent")
     *
     * @var string
     */
    const INDEX_ACTION_SETTINGS = 'pimcore.admin.indexAction.settings';

    /**
     * Fired before the request params are parsed.
     *
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Searchadmin\SearchController
     * Arguments:
     *  - requestParams | contains the request parameters
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const SEARCH_LIST_BEFORE_FILTER_PREPARE = 'pimcore.admin.search.list.beforeFilterPrepare';

    /**
     * Allows you to modify the search backend list before it is loaded.
     *
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Searchadmin\SearchController
     * Arguments:
     *  - list | the search backend list
     *  - context | contains contextual information
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const SEARCH_LIST_BEFORE_LIST_LOAD = 'pimcore.admin.search.list.beforeListLoad';

    /**
     * Allows you to modify the the result after the list was loaded.
     *
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Searchadmin\SearchController
     * Arguments:
     *  - list | raw result as an array
     *  - context | contains contextual information
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const SEARCH_LIST_AFTER_LIST_LOAD = 'pimcore.admin.search.list.afterListLoad';

    /**
     * Fired before the request params are parsed. This event apply to the grid list.
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - requestParams | contains the request parameters
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const OBJECT_LIST_BEFORE_FILTER_PREPARE = 'pimcore.admin.object.list.beforeFilterPrepare';

    /**
     * Allows you to modify the object list before it is loaded. This is a global event (search list, grid list, tree list, ...).
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - list | the object list
     *  - context | contains contextual information
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const OBJECT_LIST_BEFORE_LIST_LOAD = 'pimcore.admin.object.list.beforeListLoad';

    /**
     * Allows you to modify the object list before it is prepared for export.
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - list | the object list
     *  - context | contains contextual information
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const OBJECT_LIST_BEFORE_EXPORT_PREPARE = 'pimcore.admin.object.list.beforeExportPrepare';

    /**
     * Allows you to modify the object list before it is exported.
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - list | the object list
     *  - context | contains contextual information
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const OBJECT_LIST_BEFORE_EXPORT = 'pimcore.admin.object.list.beforeExport';

    /**
     * Allows you to modify the the result after the list was loaded. This event apply to the grid list.
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - list | raw result as an array
     *  - context | contains contextual information
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const OBJECT_LIST_AFTER_LIST_LOAD = 'pimcore.admin.object.list.afterListLoad';

    /**
     * Fired before the request params are parsed. This event apply to both the folder content preview list and the grid list.
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - requestParams | contains the request parameters
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const ASSET_LIST_BEFORE_FILTER_PREPARE = 'pimcore.admin.asset.list.beforeFilterPrepare';

    /**
     * Allows you to modify the asset list before it is loaded. This is a global event (folder content preview list, grid list, tree list, ...).
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - list | the object list
     *  - context | contains contextual information
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const ASSET_LIST_BEFORE_LIST_LOAD = 'pimcore.admin.asset.list.beforeListLoad';

    /**
     * Arguments:
     *  - field
     *  - language
     *  - keyPrefix
     *  - processed
     *  - result
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const ASSET_GET_FIELD_GRID_CONFIG = 'pimcore.admin.asset.getFieldGridConfig';

    /**
     * Allows you to modify the the result after the list was loaded. This event apply to both the folder content preview list and the grid list.
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - list | raw result as an array
     *  - context | contains contextual information
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const ASSET_LIST_AFTER_LIST_LOAD = 'pimcore.admin.asset.list.afterListLoad';

    /**
     * Allows you to modify the data from the listfolder grid before it gets processed
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - data | raw data as an array
     *  - processed | true to stop processing
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const ASSET_LIST_BEFORE_UPDATE = 'pimcore.admin.asset.list.beforeUpdate';

    /**
     * Allows you to modify the batch update data from the listfolder grid before it gets processed
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - params |
     *  - processed | true to stop processing
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const ASSET_LIST_BEFORE_BATCH_UPDATE = 'pimcore.admin.asset.list.beforeBatchUpdate';

    /**
     * Fired before the request params are parsed. This event apply to the seo panel tree.
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - requestParams | contains the request parameters
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const DOCUMENT_LIST_BEFORE_FILTER_PREPARE = 'pimcore.admin.document.list.beforeFilterPrepare';

    /**
     * Allows you to modify the document list before it is loaded. This is a global event (seo panel tree, tree list, ...).
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - list | the object list
     *  - context | contains contextual information
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const DOCUMENT_LIST_BEFORE_LIST_LOAD = 'pimcore.admin.document.list.beforeListLoad';

    /**
     * Allows you to modify the the result after the list was loaded. This event apply to the seo panel tree.
     *
     * Subject: A controller extending \Pimcore\Bundle\AdminBundle\Controller\AdminController
     * Arguments:
     *  - list | raw result as an array
     *  - context | contains contextual information
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const DOCUMENT_LIST_AFTER_LIST_LOAD = 'pimcore.admin.document.list.afterListLoad';

    /**
     * Fired before the request params are parsed.
     *
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\Asset\AssetController
     * Arguments:
     *  - data | array | the response data, this can be modified
     *  - asset | Asset | the current asset
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const ASSET_GET_PRE_SEND_DATA = 'pimcore.admin.asset.get.preSendData';

    /**
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\Asset\AssetController
     * Arguments:
     *  - assets | array | the list of asset tree nodes
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const ASSET_TREE_GET_CHILDREN_BY_ID_PRE_SEND_DATA = 'pimcore.admin.asset.treeGetChildrenById.preSendData';

    /**
     * Fired before the request params are parsed.
     *
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\ElementControllerBase
     * Arguments:
     *  - data | array | the response data, this can be modified
     *  - document | Document | the current document
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const DOCUMENT_GET_PRE_SEND_DATA = 'pimcore.admin.document.get.preSendData';

    /**
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\DocumentController
     * Arguments:
     *  - documents | array | the list of document tree nodes
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const DOCUMENT_TREE_GET_CHILDREN_BY_ID_PRE_SEND_DATA = 'pimcore.admin.document.treeGetChildrenById.preSendData';

    /**
     * Fired before the request params are parsed.
     *
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\DataObjectController
     * Arguments:
     *  - data | array | the response data, this can be modified
     *  - object | AbstractObject | the current object
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const OBJECT_GET_PRE_SEND_DATA = 'pimcore.admin.dataobject.get.preSendData';

    /**
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\DataObject\DataObjectHelperController
     * Arguments:
     *  - data | array | the response data which this can be modified
     *  - request | Request | the Request object passed to the action
     *  - config | Config | the Config object passed to the action
     *  - context | string | 'get' or 'delete'
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const OBJECT_GRID_GET_COLUMN_CONFIG_PRE_SEND_DATA = 'pimcore.admin.dataobject.gridGetColumnConfig.preSendData';

    /**
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\DataObjectController
     * Arguments:
     *  - objects | array | the list of object tree nodes
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const OBJECT_TREE_GET_CHILDREN_BY_ID_PRE_SEND_DATA = 'pimcore.admin.dataobject.treeGetChildrenById.preSendData';

    /**
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\ClassController
     * Arguments:
     *  - list | array | the list of field collections
     *  - objectId | int | id of the origin object
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const CLASS_FIELDCOLLECTION_LIST_PRE_SEND_DATA = 'pimcore.admin.class.fieldcollectionList.preSendData';

    /**
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\ClassController
     * Arguments:
     *  - icons | array | the list of selectable icons
     *  - classId | string | classid of class definition
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const CLASS_OBJECT_ICONS_PRE_SEND_DATA = 'pimcore.admin.class.dataobject.preSendData';

    /**
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\ClassController
     * Arguments:
     *  - list | array | the list of object bricks
     *  - objectId | int | id of the origin object
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const CLASS_OBJECTBRICK_LIST_PRE_SEND_DATA = 'pimcore.admin.class.objectbrickList.preSendData';

    /**
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\ClassController
     * Arguments:
     *  - brickDefinition | the brick definition
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const CLASS_OBJECTBRICK_UPDATE_DEFINITION = 'pimcore.admin.class.objectbrick.updateDefinition';

    /**
     * Allows you to modify the search backend list before it is loaded.
     *
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Searchadmin\SearchController
     * Arguments:
     *  - list | the search backend list
     *  - context | contains contextual information
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const QUICKSEARCH_LIST_BEFORE_LIST_LOAD = 'pimcore.admin.quickSearch.list.beforeListLoad';

    /**
     * Allows you to modify the the result after the list was loaded.
     *
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Searchadmin\SearchController
     * Arguments:
     *  - list | raw result as an array
     *  - context | contains contextual information
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const QUICKSEARCH_LIST_AFTER_LIST_LOAD = 'pimcore.admin.quickSearch.list.afterListLoad';

    /**
     * Fired before the an element is opened
     *
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\ElementController
     * Arguments:
     *  - type element type
     *  - id
     *
     * @Event("Pimcore\Event\Model\ResolveElementEvent")
     *
     * @var string
     */
    const RESOLVE_ELEMENT = 'pimcore.admin.resolve.element';

    /**
     * Fired before the an element is opened
     *
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\ElementController
     * Arguments:
     *     none
     *
     * @Event("Pimcore\Event\Admin\ElementAdminStyleEvent")
     *
     * @var string
     */
    const RESOLVE_ELEMENT_ADMIN_STYLE = 'pimcore.admin.resolve.elementAdminStyle';

    /**
     * Allows you to modify whether a permission on an element is granted or not
     *
     * Subject: \Pimcore\Model\Element\AbstractElement
     * Arguments:
     *  - isAllowed | bool | the original "isAllowed" value as determined by pimcore. This can be modfied
     *  - permissionType | string | the permission that is checked
     *  - user | \Pimcore\Model\User | user the permission is checked for
     *
     * @Event("Pimcore\Event\Model\ElementEvent")
     *
     * @var string
     */
    const ELEMENT_PERMISSION_IS_ALLOWED = 'pimcore.admin.permissions.elementIsAllowed';

    /**
     * Subject: \Pimcore\Bundle\AdminBundle\Controller\Admin\Asset\AssetController
     * Arguments:
     *  - id | int | asset id
     *  - metadata | array | contains the data received from the editor UI
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const ASSET_METADATA_PRE_SET = 'pimcore.admin.asset.metadata.preSave';

    /**
     * This event is fired after Pimcore generates the runtime Perspective
     *
     * Arguments:
     *  - result | The result array
     *  - configName | Name of the current perspective
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    public const PERSPECTIVE_POST_GET_RUNTIME = 'pimcore.admin.perspective.postGetRuntime';
}
