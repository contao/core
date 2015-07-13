<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

/**
 * This file is not used in Contao. Its only purpose is to make PHP IDEs like
 * Eclipse, Zend Studio or PHPStorm realize the class origins, since the dynamic
 * class aliasing we are using is a bit too complex for them to understand.
 */

// TL_ROOT
namespace {
	define('TL_ROOT', __DIR__ . '../../../');
	define('TL_ASSETS_URL', 'http://localhost/');
	define('TL_FILES_URL', 'http://localhost/');
}

// calendar
namespace  {
	class Calendar extends \Contao\Calendar {}
	abstract class Events extends \Contao\Events {}
	class CalendarEventsModel extends \Contao\CalendarEventsModel {}
	class CalendarFeedModel extends \Contao\CalendarFeedModel {}
	class CalendarModel extends \Contao\CalendarModel {}
	class ModuleCalendar extends \Contao\ModuleCalendar {}
	class ModuleEventlist extends \Contao\ModuleEventlist {}
	class ModuleEventMenu extends \Contao\ModuleEventMenu {}
	class ModuleEventReader extends \Contao\ModuleEventReader {}
}

// comments
namespace  {
	class Comments extends \Contao\Comments {}
	class ContentComments extends \Contao\ContentComments {}
	class CommentsModel extends \Contao\CommentsModel {}
	class CommentsNotifyModel extends \Contao\CommentsNotifyModel {}
	class ModuleComments extends \Contao\ModuleComments {}
}

// core
namespace  {
	class Ajax extends \Contao\Ajax {}
	abstract class Backend extends \Contao\Backend {}
	abstract class BackendModule extends \Contao\BackendModule {}
	class BackendTemplate extends \Contao\BackendTemplate {}
	class BackendUser extends \Contao\BackendUser {}
	abstract class DataContainer extends \Contao\DataContainer {}
	class DropZone extends \Contao\DropZone {}
	class FileUpload extends \Contao\FileUpload {}
	abstract class Frontend extends \Contao\Frontend {}
	class FrontendTemplate extends \Contao\FrontendTemplate {}
	class FrontendUser extends \Contao\FrontendUser {}
	abstract class Hybrid extends \Contao\Hybrid {}
	class LiveUpdate extends \Contao\LiveUpdate {}
	class Messages extends \Contao\Messages {}
	class PurgeData extends \Contao\PurgeData {}
	class RebuildIndex extends \Contao\RebuildIndex {}
	class StyleSheets extends \Contao\StyleSheets {}
	class Theme extends \Contao\Theme {}
	class Versions extends \Contao\Versions {}
	class BackendChangelog extends \Contao\BackendChangelog {}
	class BackendConfirm extends \Contao\BackendConfirm {}
	class BackendFile extends \Contao\BackendFile {}
	class BackendHelp extends \Contao\BackendHelp {}
	class BackendIndex extends \Contao\BackendIndex {}
	class BackendInstall extends \Contao\BackendInstall {}
	class BackendMain extends \Contao\BackendMain {}
	class BackendPage extends \Contao\BackendPage {}
	class BackendPassword extends \Contao\BackendPassword {}
	class BackendPopup extends \Contao\BackendPopup {}
	class BackendPreview extends \Contao\BackendPreview {}
	class BackendSwitch extends \Contao\BackendSwitch {}
	class FrontendCron extends \Contao\FrontendCron {}
	class FrontendIndex extends \Contao\FrontendIndex {}
	class FrontendShare extends \Contao\FrontendShare {}
	class DC_File extends \Contao\DC_File {}
	class DC_Folder extends \Contao\DC_Folder {}
	class DC_Table extends \Contao\DC_Table {}
	class ContentAccordion extends \Contao\ContentAccordion {}
	class ContentAccordionStart extends \Contao\ContentAccordionStart {}
	class ContentAccordionStop extends \Contao\ContentAccordionStop {}
	class ContentAlias extends \Contao\ContentAlias {}
	class ContentArticle extends \Contao\ContentArticle {}
	class ContentCode extends \Contao\ContentCode {}
	class ContentDownload extends \Contao\ContentDownload {}
	class ContentDownloads extends \Contao\ContentDownloads {}
	abstract class ContentElement extends \Contao\ContentElement {}
	class ContentGallery extends \Contao\ContentGallery {}
	class ContentHeadline extends \Contao\ContentHeadline {}
	class ContentHtml extends \Contao\ContentHtml {}
	class ContentHyperlink extends \Contao\ContentHyperlink {}
	class ContentImage extends \Contao\ContentImage {}
	class ContentList extends \Contao\ContentList {}
	class ContentMarkdown extends \Contao\ContentMarkdown {}
	class ContentMedia extends \Contao\ContentMedia {}
	class ContentModule extends \Contao\ContentModule {}
	class ContentSliderStart extends \Contao\ContentSliderStart {}
	class ContentSliderStop extends \Contao\ContentSliderStop {}
	class ContentTable extends \Contao\ContentTable {}
	class ContentTeaser extends \Contao\ContentTeaser {}
	class ContentText extends \Contao\ContentText {}
	class ContentToplink extends \Contao\ContentToplink {}
	class ContentYouTube extends \Contao\ContentYouTube {}
	class Form extends \Contao\Form {}
	class FormCaptcha extends \Contao\FormCaptcha {}
	class FormCheckBox extends \Contao\FormCheckBox {}
	class FormExplanation extends \Contao\FormExplanation {}
	class FormFieldset extends \Contao\FormFieldset {}
	class FormFileUpload extends \Contao\FormFileUpload {}
	class FormHeadline extends \Contao\FormHeadline {}
	class FormHidden extends \Contao\FormHidden {}
	class FormHtml extends \Contao\FormHtml {}
	class FormPassword extends \Contao\FormPassword {}
	class FormRadioButton extends \Contao\FormRadioButton {}
	class FormSelectMenu extends \Contao\FormSelectMenu {}
	class FormSubmit extends \Contao\FormSubmit {}
	class FormTextArea extends \Contao\FormTextArea {}
	class FormTextField extends \Contao\FormTextField {}
	class Automator extends \Contao\Automator {}
	abstract class BaseTemplate extends \Contao\BaseTemplate {}
	class Cache extends \Contao\Cache {}
	class ClassLoader extends \Contao\ClassLoader {}
	class Combiner extends \Contao\Combiner {}
	class Config extends \Contao\Config {}
	abstract class Controller extends \Contao\Controller {}
	abstract class Database extends \Contao\Database {}
	class Date extends \Contao\Date {}
	class Dbafs extends \Contao\Dbafs {}
	class DcaExtractor extends \Contao\DcaExtractor {}
	class DcaLoader extends \Contao\DcaLoader {}
	class DiffRenderer extends \Contao\DiffRenderer {}
	class Email extends \Contao\Email {}
	class Encryption extends \Contao\Encryption {}
	class Environment extends \Contao\Environment {}
	class Feed extends \Contao\Feed {}
	class FeedItem extends \Contao\FeedItem {}
	class File extends \Contao\File {}
	abstract class Files extends \Contao\Files {}
	class Folder extends \Contao\Folder {}
	class GdImage extends \Contao\GdImage {}
	class Idna extends \Contao\Idna {}
	class Image extends \Contao\Image {}
	class Input extends \Contao\Input {}
	class Message extends \Contao\Message {}
	abstract class Model extends \Contao\Model {}
	class ModuleLoader extends \Contao\ModuleLoader {}
	class Pagination extends \Contao\Pagination {}
	class Picture extends \Contao\Picture {}
	class Request extends \Contao\Request {}
	class RequestToken extends \Contao\RequestToken {}
	class Search extends \Contao\Search {}
	class Session extends \Contao\Session {}
	class SortedIterator extends \Contao\SortedIterator {}
	class String extends \Contao\String {}
	abstract class System extends \Contao\System {}
	abstract class Template extends \Contao\Template {}
	class TemplateLoader extends \Contao\TemplateLoader {}
	abstract class User extends \Contao\User {}
	class Validator extends \Contao\Validator {}
	abstract class Widget extends \Contao\Widget {}
	class ZipReader extends \Contao\ZipReader {}
	class ZipWriter extends \Contao\ZipWriter {}
	class ArticleModel extends \Contao\ArticleModel {}
	class ContentModel extends \Contao\ContentModel {}
	class FilesModel extends \Contao\FilesModel {}
	class FormFieldModel extends \Contao\FormFieldModel {}
	class FormModel extends \Contao\FormModel {}
	class ImageSizeItemModel extends \Contao\ImageSizeItemModel {}
	class ImageSizeModel extends \Contao\ImageSizeModel {}
	class LayoutModel extends \Contao\LayoutModel {}
	class MemberGroupModel extends \Contao\MemberGroupModel {}
	class MemberModel extends \Contao\MemberModel {}
	class ModuleModel extends \Contao\ModuleModel {}
	class PageModel extends \Contao\PageModel {}
	class SessionModel extends \Contao\SessionModel {}
	class StyleModel extends \Contao\StyleModel {}
	class StyleSheetModel extends \Contao\StyleSheetModel {}
	class ThemeModel extends \Contao\ThemeModel {}
	class UserGroupModel extends \Contao\UserGroupModel {}
	class UserModel extends \Contao\UserModel {}
	abstract class Module extends \Contao\Module {}
	class ModuleArticle extends \Contao\ModuleArticle {}
	class ModuleArticleList extends \Contao\ModuleArticleList {}
	class ModuleArticlenav extends \Contao\ModuleArticlenav {}
	class ModuleBooknav extends \Contao\ModuleBooknav {}
	class ModuleBreadcrumb extends \Contao\ModuleBreadcrumb {}
	class ModuleChangePassword extends \Contao\ModuleChangePassword {}
	class ModuleCloseAccount extends \Contao\ModuleCloseAccount {}
	class ModuleCustomnav extends \Contao\ModuleCustomnav {}
	class ModuleFlash extends \Contao\ModuleFlash {}
	class ModuleHtml extends \Contao\ModuleHtml {}
	class ModuleLogin extends \Contao\ModuleLogin {}
	class ModuleLogout extends \Contao\ModuleLogout {}
	class ModuleMaintenance extends \Contao\ModuleMaintenance {}
	class ModuleNavigation extends \Contao\ModuleNavigation {}
	class ModulePassword extends \Contao\ModulePassword {}
	class ModulePersonalData extends \Contao\ModulePersonalData {}
	class ModuleQuicklink extends \Contao\ModuleQuicklink {}
	class ModuleQuicknav extends \Contao\ModuleQuicknav {}
	class ModuleRandomImage extends \Contao\ModuleRandomImage {}
	class ModuleRegistration extends \Contao\ModuleRegistration {}
	class ModuleRssReader extends \Contao\ModuleRssReader {}
	class ModuleSearch extends \Contao\ModuleSearch {}
	class ModuleSitemap extends \Contao\ModuleSitemap {}
	class ModuleUser extends \Contao\ModuleUser {}
	class PageError403 extends \Contao\PageError403 {}
	class PageError404 extends \Contao\PageError404 {}
	class PageForward extends \Contao\PageForward {}
	class PageRedirect extends \Contao\PageRedirect {}
	class PageRegular extends \Contao\PageRegular {}
	class PageRoot extends \Contao\PageRoot {}
	class CheckBox extends \Contao\CheckBox {}
	class CheckBoxWizard extends \Contao\CheckBoxWizard {}
	class ChmodTable extends \Contao\ChmodTable {}
	class FileSelector extends \Contao\FileSelector {}
	class FileTree extends \Contao\FileTree {}
	class ImageSize extends \Contao\ImageSize {}
	class InputUnit extends \Contao\InputUnit {}
	class KeyValueWizard extends \Contao\KeyValueWizard {}
	class ListWizard extends \Contao\ListWizard {}
	class MetaWizard extends \Contao\MetaWizard {}
	class ModuleWizard extends \Contao\ModuleWizard {}
	class OptionWizard extends \Contao\OptionWizard {}
	class PageSelector extends \Contao\PageSelector {}
	class PageTree extends \Contao\PageTree {}
	class Password extends \Contao\Password {}
	class RadioButton extends \Contao\RadioButton {}
	class RadioTable extends \Contao\RadioTable {}
	class SelectMenu extends \Contao\SelectMenu {}
	class TableWizard extends \Contao\TableWizard {}
	class TextArea extends \Contao\TextArea {}
	class TextField extends \Contao\TextField {}
	class TextStore extends \Contao\TextStore {}
	class TimePeriod extends \Contao\TimePeriod {}
	class TrblField extends \Contao\TrblField {}
	class Upload extends \Contao\Upload {}
}
namespace Database {
	class Installer extends \Contao\Database\Installer {}
	class Mysql extends \Contao\Database\Mysql {}
	class Mysqli extends \Contao\Database\Mysqli {}
	abstract class Result extends \Contao\Database\Result {}
	abstract class Statement extends \Contao\Database\Statement {}
	class Updater extends \Contao\Database\Updater {}
}
namespace Database\Mysql {
	class Result extends \Contao\Database\Mysql\Result {}
	class Statement extends \Contao\Database\Mysql\Statement {}
}
namespace Database\Mysqli {
	class Result extends \Contao\Database\Mysqli\Result {}
	class Statement extends \Contao\Database\Mysqli\Statement {}
}
namespace Files {
	class Ftp extends \Contao\Files\Ftp {}
	class Php extends \Contao\Files\Php {}
}
namespace Filter {
	class SqlFiles extends \Contao\Filter\SqlFiles {}
	class SyncExclude extends \Contao\Filter\SyncExclude {}
}
namespace Model {
	class Collection extends \Contao\Model\Collection {}
	class QueryBuilder extends \Contao\Model\QueryBuilder {}
	class Registry extends \Contao\Model\Registry {}
}

// devtools
namespace  {
	class ModuleAutoload extends \Contao\ModuleAutoload {}
	class ModuleExtension extends \Contao\ModuleExtension {}
	class ModuleLabels extends \Contao\ModuleLabels {}
}

// faq
namespace  {
	class FaqCategoryModel extends \Contao\FaqCategoryModel {}
	class FaqModel extends \Contao\FaqModel {}
	class ModuleFaq extends \Contao\ModuleFaq {}
	class ModuleFaqList extends \Contao\ModuleFaqList {}
	class ModuleFaqPage extends \Contao\ModuleFaqPage {}
	class ModuleFaqReader extends \Contao\ModuleFaqReader {}
}

// listing
namespace  {
	class ModuleListing extends \Contao\ModuleListing {}
}

// news
namespace  {
	class News extends \Contao\News {}
	class NewsArchiveModel extends \Contao\NewsArchiveModel {}
	class NewsFeedModel extends \Contao\NewsFeedModel {}
	class NewsModel extends \Contao\NewsModel {}
	abstract class ModuleNews extends \Contao\ModuleNews {}
	class ModuleNewsArchive extends \Contao\ModuleNewsArchive {}
	class ModuleNewsList extends \Contao\ModuleNewsList {}
	class ModuleNewsMenu extends \Contao\ModuleNewsMenu {}
	class ModuleNewsReader extends \Contao\ModuleNewsReader {}
}

// newsletter
namespace  {
	class Newsletter extends \Contao\Newsletter {}
	class NewsletterChannelModel extends \Contao\NewsletterChannelModel {}
	class NewsletterModel extends \Contao\NewsletterModel {}
	class NewsletterRecipientsModel extends \Contao\NewsletterRecipientsModel {}
	class ModuleNewsletterList extends \Contao\ModuleNewsletterList {}
	class ModuleNewsletterReader extends \Contao\ModuleNewsletterReader {}
	class ModuleSubscribe extends \Contao\ModuleSubscribe {}
	class ModuleUnsubscribe extends \Contao\ModuleUnsubscribe {}
}
