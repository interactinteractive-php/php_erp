<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');

class Appmarket extends Controller {
    
    private static $standartColors = [
        '#FF7E79',
        '#6373ED',
        '#39E0CF',
        '#FEC345',
        '#48C7F4',
        '#FF8E50',
        '#36C09A',
        '#7A93A0',
        '#A74FB6',
        '#9C7D72',
        '#01BCD4',
        '#EB6FA5',
        '#FEB69C',
        '#9C7D72',
        '#FFEB6D',
        '#FFCC99'
    ];

    public function __construct() {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function index() 
    {   
        $this->view->title = 'App market'; 
        
        $this->view->css = array_unique(array_merge(array('custom/css/appmarket.css'), AssetNew::metaCss()));
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
        
        $this->view->leftMenuData = $this->model->getDataviewResultModel(1700449463973796, 100);
        $this->view->leftIndustryMenuData = $this->model->getDataviewResultModel(1700453026051099, 100);
        $this->view->standartColors = self::$standartColors;
        
        $this->view->render('header');
        $this->view->render('appmarket/appmarket');
        $this->view->render('footer');
    }        

    public function detail($id) 
    {   
        $this->view->title = 'App market detail'; 
        
        $this->view->css = array_unique(array_merge(array('custom/css/appmarket.css'), AssetNew::metaCss()));
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));
        
        $this->view->leftMenuData = $this->model->getDataviewResultModel(1700449463973796, 100);
        $this->view->leftIndustryMenuData = $this->model->getDataviewResultModel(1700453026051099, 100);

        $this->view->render('header');
        $this->view->render('appmarket/appmarket-detail');
        $this->view->render('footer');
    }    
    
    
    public function basket($menuCode = null) 
    {   
        $this->view->title = 'App market basket'; 
        
        $this->view->css = array_unique(array_merge(array('custom/css/appmarket.css'), AssetNew::metaCss()));
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

        $this->view->render('header');
        $this->view->render('appmarket/appmarket-basket');
        $this->view->render('footer');
    }

    public function basketOther($menuCode = null) 
    {   
        $this->view->title = 'App market basket'; 
        
        $this->view->css = array_unique(array_merge(array('custom/css/appmarket.css'), AssetNew::metaCss()));
        $this->view->js = array_unique(array_merge(array('custom/addon/admin/pages/scripts/app.js'), AssetNew::metaOtherJs()));

        $this->view->render('header');
        $this->view->render('appmarket/appmarket-basket1');
        $this->view->render('footer');
    }
    
}