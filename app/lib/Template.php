<?php


namespace MUSICAA\lib;


class Template
{
    private $_template_parts;
    private $_action_view;
    private $_data;

    public function __construct(array $parts)
    {
        $this->_template_parts = $parts;
    }

    public function setActionViewFile($actionView){
        $this->_action_view = $actionView;
    }

    public function setAppData($data){
        $this->_data = $data;
    }

    private function renderTemplateHeaderStart(){
        extract($this->_data);
        require_once TEMPLATE_PATH . 'templateheaderstart.php';
    }

    private function renderTemplateHeaderEnd(){
        extract($this->_data);
        require_once TEMPLATE_PATH . 'templateheaderend.php';
    }

    private function renderTemplateFooter(){
        extract($this->_data);
        require_once TEMPLATE_PATH . 'templatefooter.php';
    }

    private function renderTemplateBlocks(){
        if (!array_key_exists('template', $this->_template_parts)){
            trigger_error('Sorry There Is No Template Blocks In Your Project',E_USER_WARNING);
        }else{
            extract($this->_data);

            $parts = $this->_template_parts['template'];
            if (!empty($parts)){
                foreach ($parts as $partKey => $file){
                    if ($partKey == ':view'){
                        require_once $this->_action_view;
                    }else{
                        require_once $file;
                    }
                }
            }
        }
    }

    private function renderHeaderResources(){
        extract($this->_data);

        $output = '';
        if (!array_key_exists('header', $this->_template_parts)){
            trigger_error('Sorry There Is No Header Resources Blocks In Your Project',E_USER_WARNING);
        }else{
            $resources = $this->_template_parts['header'];

            $css = $resources['css'];
            if (!empty($css)){
                foreach ($css as $cssKey => $file){
                    if ($cssKey == 'fontawsom'){
                        $output .= "<link rel='stylesheet' href='$file' integrity='sha384-KA6wR/X5RY4zFAHpv/CnoG2UW1uogYfdnP67Uv7eULvTveboZJg0qUpmJZb5VqzN' crossorigin='anonymous' />";
                    }else{
                        $output .= "<link rel='stylesheet' href='$file' />";
                    }
                }
            }

            $js = $resources['js'];
            if (!empty($js)){
                foreach ($js as $jsKey => $file){
                    $output .= "<script src='$file'></script>";
                }
            }

        }

        echo $output;

    }

    private function renderFooterResources(){
        extract($this->_data);

        $output = '';
        if (!array_key_exists('footer', $this->_template_parts)){
            trigger_error('Sorry There Is No Header Resources Blocks In Your Project',E_USER_WARNING);
        }else{
            $js = $this->_template_parts['footer']['js'];

            if (!empty($js)){
                foreach ($js as $jsKey => $file){
                    $output .= "<script src='$file'></script>";
                }
            }

        }

        echo $output;
    }

    public function renderApp(){
        $this->renderTemplateHeaderStart();
        $this->renderHeaderResources();
        $this->renderTemplateHeaderEnd();
        $this->renderTemplateBlocks();
        $this->renderFooterResources();
        $this->renderTemplateFooter();
    }
}