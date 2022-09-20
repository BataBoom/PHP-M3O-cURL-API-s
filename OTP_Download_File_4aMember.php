<?php

class Am_Plugin_DownloadLink extends Am_Plugin
{
    const PLUGIN_STATUS = self::STATUS_PRODUCTION;
    const PLUGIN_COMM = self::COMM_COMMERCIAL;
    const PLUGIN_REVISION = '@@VERSION@@';

    protected $_configPrefix = 'misc.';

    static function getDbXml()
    {
        return <<<CUT
<schema version="4.0.0">
    <table name="download_token">
        <field name="id" type="int" unsigned="1" notnull="1" extra="auto_increment" />
        <field name="user_id" type="int" unsigned="1" notnull="1" />
        <field name="token" type="varchar" len="10" />
        <field name="expire" type="datetime" notnull="1" />
        <index name="PRIMARY" unique="1">
            <field name="id" />
        </index>
    </table>
    <table name="user">
        <field name="last_token_request" type="datetime" />
    </table>
</schema>
CUT;
    }

    function _initSetupForm(Am_Form_Setup $form)
    {
        $form->addSecretText('email_token')
            ->setLabel("Email Token\napi.m3o.com")
            ->addRule('required');

        $form->addMagicSelect('access', ['class' => 'am-combobox'])
            ->setLabel("Products\nthat grant access to service, keep empty to allow access for all")
            ->loadOptions($this->getDi()->productTable->getProductOptions());
    }

    function isConfigured()
    {
        return $this->getConfig('email_token');
    }

    function hasAccess(User $user)
    {
        if (!$this->getConfig('access')) return true;

        return (bool) array_intersect($user->getActiveProductIds(), $this->getDi()->productTable->extractProductIds($this->getConfig('access')));
    }

    function onUserMenuItems(Am_Event $e)
    {
        $e->addReturn([$this, 'buildMenu'], 'download-link');
    }

    function buildMenu(Am_Navigation_Container $nav, User $user, $order, $config)
    {
        if ($this->hasAccess($user)) {
            return $nav->addPage([
                'id' => $this->getId(),
                'label' => 'Get Download Link',
                'controller' => 'download-link',
                'action' => 'index',
                'module' => 'default',
                'order' => $order,
            ], true);
        }
    }

    function onHourly(Am_Event $e)
    {
        $this->getDi()->downloadTokenTable->deleteBy([['expire', '<', sqlTime('now')]]);
    }
}

class DownloadLinkController extends Am_Mvc_Controller
{
    protected $session;

    function preDispatch()
    {
        $this->getDi()->auth->requireLogin();
        if (!$this->getPlugin()->hasAccess($this->getDi()->user)) {
            throw new Am_Exception_AccessDenied;
        }
    }

    function indexAction()
    {
        $this->view->title = '2Factor Authentication';

        if (!$this->canMakeRequest($this->getDi()->user)) {
            $this->view->content = "<p>You can request token only once per 5 minutes</p>";
            $this->view->display('member/layout.phtml');
            return;
        }

        if ($this->hasValidCode()) {
            Am_Mvc_Response::redirectLocation($this->getDi()->surl('download-link/validate'));
        }

        $form = new Am_Form();
        $form->addHtml()
            ->setHtml($this->getDi()->user->email)
            ->setLabel('E-Mail Address');
        $form->addSaveButton('Request Token');

        if ($form->isSubmitted() && $form->validate()) {
            $this->getSession()->code = $code = strtoupper($this->getDi()->security->randomString(5));
            $this->getSession()->code_expire = strtotime("+5minutes");

            $emailtoken = $this->getPlugin()->getConfig('email_token');
            $omg = new Am_HttpRequest('https://api.m3o.com/v1/email/Send', Am_HttpRequest::METHOD_POST);
            $omg->setHeader('Content-type: application/json');
            $omg->setHeader("Authorization: Bearer $emailtoken");
            $omg->setBody(json_encode([
                'from' => "YourBrand",
                'subject' => "One Time Password",
                'textBody' => "Your One Time Pass Code: $code",
                'to' => $this->getDi()->user->email
            ]));
            $omg->send();

            Am_Mvc_Response::redirectLocation($this->getDi()->surl('download-link/validate'));
        } else {
            $this->view->content = $form;
            $this->view->display('member/layout.phtml');
        }
    }

    function validateAction()
    {
        if (!$this->hasValidCode()) {
            Am_Mvc_Response::redirectLocation($this->getDi()->surl('download-link'));
        }

        $form = new Am_Form();
        $form->addHtml(null, ['class' => 'am-el-wide am-no-label'])
            ->setHtml(<<<CUT
<p>Sent! Check your email! Then Fill in the Field Below</p>
CUT
);
        $form->addText('code')
            ->setLabel('Code')
            ->addRule('callback', ___('Incorrect Verification Code'), [$this, 'isValidCode']);

        $form->addSaveButton('Validate');

        if ($form->isSubmitted() && $form->validate()) {

            $token = $this->getDi()->downloadTokenTable->generate($this->getDi()->user);

            $this->getDi()->user->last_token_request = sqlTime('now');
            $this->getDi()->user->save();

            $this->getSession()->unsetAll();

            Am_Mvc_Response::redirectLocation($this->getDi()->surl('download-link/token', ['t' => $token], false));

        } else {
            $this->view->title = '2Factor Authentication';
            $this->view->content = $form;
            $this->view->display('member/layout.phtml');
        }
    }

    function tokenAction()
    {
        $t = filterId($this->getParam('t'));

        $this->view->title = 'Request Download';
        $url = Am_Html::escape("/Download.php?t=$t");
        $this->view->content = <<<CUT
<p><a href="$url" target="_blank">Download</a></p>
CUT;
        $this->view->display('member/layout.phtml');

    }

    function getSession()
    {
        if (!$this->session) {
            $this->session = new Zend_Session_Namespace("misc.download-link");
        }
        return $this->session;
    }

    function isValidCode($code)
    {
        return $this->getSession()->code && $this->getSession()->code_expire > time() && $this->getSession()->code == $code;
    }

    function hasValidCode()
    {
        return $this->getSession()->code && $this->getSession()->code_expire > time();
    }

    function canMakeRequest($user)
    {
        return empty($user->last_token_request) || $user->last_token_request < sqlTime("-5minutes");
    }

    function getPlugin()
    {
        return $this->getDi()->plugins_misc->loadGet('download-link');
    }
}

class DownloadToken extends Am_Record
{
}

class DownloadTokenTable extends Am_Table
{
    protected $_table = '?_download_token';
    protected $_key = 'id';
    protected $_recordClass = 'DownloadToken';

    function generate(User $user)
    {
        $r = $this->getDi()->downloadTokenRecord;
        $r->token = $this->generateTokenCode();
        $r->user_id = $user->pk();
        $r->expire = sqlTime('+20minutes');
        $r->save();

        return $r->token;
    }

    function generateTokenCode()
    {
        do {
            $token = strtoupper($this->getDi()->security->randomString(10, 'WERTYUPLKJHGFDSAZXCVBNM23456789'));
        } while ($this->findByToken($token));

        return $token;
    }
}
