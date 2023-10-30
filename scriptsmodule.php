<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class ScriptsModule extends Module
{
    public function __construct()
    {
        $this->name = 'scriptsmodule';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Julio Marichales';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Scripts Module');
        $this->description = $this->l('Description of my module.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }

        public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

    return (
            parent::install() 
            && Configuration::updateValue('MYMODULE_NAME', 'scriptsmodule')
            && $this->registerHook('displayTop')
            && $this->registerHook('displayFooter')
            && $this->registerHook('displayHome')
        ); 
    }

    public function uninstall()
 {
    return (
        parent::uninstall() 
        && Configuration::deleteByName('MYMODULE_NAME')
    );
 }

 /**
 * This method handles the module's configuration page
 * @return string The page's HTML content 
 */
public function getContent()
{
    $output = '';

    // this part is executed only when the form is submitted
    if (Tools::isSubmit('submit' . $this->name)) {
        // retrieve the value set by the user
        $s_header = (string) Tools::getValue('SCRIPT_HEADER');
        $s_footer = (string) Tools::getValue('SCRIPT_FOOTER');
        $add_css = (string) Tools::getValue('ADD_CSS');
        // check that the value is valid
        if (
            empty($s_header) && empty($s_footer) && empty($add_css) 
            || !Validate::isGenericName($s_header) 
             && !Validate::isGenericName($s_footer)
             && !Validate::isGenericName($add_css)
            ) {
            // invalid value, show an error
            $output = $this->displayError($this->l('Invalid Configuration value'));
        } else {
            // value is ok, update it and display a confirmation message
            html_entity_decode(Configuration::updateValue('SCRIPT_HEADER', $s_header));
            html_entity_decode(Configuration::updateValue('SCRIPT_FOOTER', $s_footer));
            html_entity_decode(Configuration::updateValue('ADD_CSS', $add_css));
            $output = $this->displayConfirmation($this->l('Settings updated'));
        }
    }
   
    // display any message, then the form
    return $output . $this->displayForm();
}
 
/**
 * Builds the configuration form
 * @return string HTML code
 * 
 */
public function displayForm()
{
    // Init Fields form array
    $form = [
        'form' => [
            'legend' => [
                'title' => $this->l('Añadir JS y CSS para Header y Footer'),
            ],
            'input' => [
                [
                    'type' => 'textarea',
                    'label' => $this->l('Header'),
                    'name' => 'SCRIPT_HEADER',
                    'size' => 40,
                    'required' => false,
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->l('Footer'),
                    'name' => 'SCRIPT_FOOTER',
                    'size' => 40,
                    'required' => false,
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->l('Añadir CSS'),
                    'name' => 'ADD_CSS',
                    'size' => 40,
                    'required' => false,
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
            ],
        ],
    ];

    $helper = new HelperForm();

    // Module, token and currentIndex
    $helper->table = $this->table;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
    $helper->submit_action = 'submit' . $this->name;

    // Default language
    $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');

    // Load current value into the form
    $helper->fields_value['SCRIPT_HEADER'] = Tools::getValue('SCRIPT_HEADER', Configuration::get('SCRIPT_HEADER'));
    $helper->fields_value['SCRIPT_FOOTER'] = Tools::getValue('SCRIPT_FOOTER', Configuration::get('SCRIPT_FOOTER'));
    $helper->fields_value['ADD_CSS'] = Tools::getValue('ADD_CSS', Configuration::get('ADD_CSS'));

    return $helper->generateForm([$form]);
}

public function HookdisplayTop(){
    $script_header = Configuration::get('SCRIPT_HEADER');
    $this->context->smarty->assign([
        'script_header' => $script_header,
    ]);
    return $this->display(__FILE__ ,'templates/hook/views/scriptsmodule_header.tpl');
  }

  public function HookdisplayFooter(){
    $script_footer = Configuration::get('SCRIPT_FOOTER');
    $this->context->smarty->assign([
        'script_footer' => $script_footer,
    ]);
    return $this->display(__FILE__ ,'templates/hook/views/scriptsmodule_footer.tpl');
  }

  public function HookdisplayHome(){
    $add_css = Configuration::get('ADD_CSS');
    $this->context->smarty->assign([
        'add_css' => $add_css,
    ]);
    return $this->display(__FILE__ ,'templates/hook/views/stylecss_module.tpl');
  }

}



